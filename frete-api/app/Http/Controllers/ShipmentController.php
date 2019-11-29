<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ShipmentController extends Controller
{

    public function listAgencies(Request $request)
    {
        /*
        OUTPUT
        [
           {
             "id": 4,
             "name": "LJ OSASCO 01",
             "initials": "LJ-OSC-01",
             "code": "1008139",
             "company_name": "MEG LOGISTICA E TRANSPORTES LTDA",
             "status": "available",
             "email": "meg.osc@jadlog.com.br",
             "address": {
               "id": 4,
               "label": "Agência JadLog",
               "postal_code": "6210130",
               "address": "Rua Armenia, 259 \/ 644",
               "number": null,
               "complement": null,
               "district": "Presidente Altino",
               "latitude": -23.5278746,
               "longitude": -46.7652875,
               "confirmed_at": null,
               "created_at": "2017-09-11 17:47:13",
               "updated_at": "2019-03-20 16:36:01",
               "city": {
                 "id": 5094,
                 "city": "Osasco",
                 "state": {
                   "id": 25,
                   "state": "São Paulo",
                   "state_abbr": "SP",
                   "country": {
                     "id": "BR",
                     "country": "Brazil"
                   }
                 }
               }
             },
             "phone": {
               "id": 4,
               "label": "Agência JadLog",
               "phone": "1136891212",
               "type": "fixed",
               "country_id": "BR",
               "confirmed_at": null,
               "created_at": "2017-09-11 17:47:13",
               "updated_at": "2019-03-20 16:36:01"
             }
           },
           {
             "id": 5,
                 (...)
        */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/shipment/agencies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function cancelShipment(Request $request)
    {
        /*
         INPUT
        --form "order_id=af3fef55-b068-4a43-8d9e-cfcda148a38c" \
        --form "order_reason_id=2" \
        --form "order_description=Descrição do cancelamento"
         */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/shipment/cancel';
        $header = array(
            'Accept: application/json',
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $token);

        $fields = array(
            'order[id]' => $request->input('order_id'),
            'order[reason_id]' => $request->input('order_reason_id'),
            'order[description]' => $request->input('order_description')
        );

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields, 'URL');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function printTag(Request $request)
    {
        /*
         * INPUT (RAW)
         * {
              "mode": "public"
              "orders": {
                    2   "{{order_id}}",
              }
            }
         */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/shipment/print';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $body = $request->all();

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $body, 'JSON');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function buyAllShipping(Request $request)
    {
        /*

        */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/shipment/checkout';
        $header = array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function buyShippingPOST(Request $request)
    {
        /*INPUT
         * --form "orders=af3fef55-b068-4a43-8d9e-cfcda148a38c" \
           --form "gateway=moip" \
           --form "redirect=https://www.klopr.com" \
           --form "wallet=19.30"
        */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/shipment/checkout';
        $header = array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token);
        $fields = array(
            'orders[]' => $request->input('orders'), //Obrigatório -- Nome de identificação no sistema
            'gateway' => $request->input('gateway'), //Opcional -- moip / mercado pago (caso nao tenha saldo suficiente na carteira)
            'redirect' => $request->input('redirect'), //Opcional -- URL para redirecionamento apos pagamento
            'wallet' => $request->input('wallet'), //Opcional -- Saldo que será utilizado da carteira
        );

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields,'URL');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function calculateShipment(Request $request) { //CALCULO DO FRETE DE UM PACOTE

        $enfpoint = 'api/v2/me/shipment/calculate';
        $url = ''; // ENDPOINT ONDE ESTARÁ O JSON DA KLOPR (Seguindo o padrão https://docs.melhorenvio.com.br/shipment/calculate.html)
        $json = file_get_contents($url);
        $json_decoded = json_decode($json);

        $fromPostalCode = $json_decoded->from[0];
        $fromAddress = $json_decoded->from[1];
        $fromNumber = $json_decoded->from[2];

        $toPostalCode = $json_decoded->to[0];
        $toAddress = $json_decoded->to[1];
        $toNumber = $json_decoded->to[2];

        $packageWeight = $json_decoded->package[0];
        $packageWidth = $json_decoded->package[1];
        $packageHeight = $json_decoded->package[2];
        $packageLenght = $json_decoded->package[3];

        $insuranceValue = $json_decoded->options[0];
        $receipt = $json_decoded->options[1];
        $ownHand = $json_decoded->options[2];
        $collect = $json_decoded->options[3];

        $services = $json_decoded->services;


        $this->validate($request, [
            'from[postal_code]' => $fromPostalCode,
            'from[address]' => $fromAddress,
            'from[number]' => $fromNumber,
            'to[postal_code]' => $toPostalCode,
            'to[address]' => $toAddress,
            'to[number]' => $toNumber,
            'package[weight]' => $packageWeight,
            'package[width]' => $packageWidth,
            'package[height]' => $packageHeight,
            'package[length]' => $packageLenght,
            'options[insurance_value]' => $insuranceValue,
            'options[receipt]' => $receipt,
            'options[collect]' => $collect,
            'options[own_hand]' => $ownHand,
            'services' => $services

        ]);
    }
}

