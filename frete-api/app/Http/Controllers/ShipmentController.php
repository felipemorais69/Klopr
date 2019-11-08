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

        $response = $this->GetDelRequestCurl(self::domainSandboxME, $endpoint, null, $header);
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
            'Content-type: application/json',
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
        $body = $request->getContent();

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $body, null);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function buyShippinggGET(Request $request)
    {
        /*

        */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/shipment/checkout';
        $header = array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetDelRequestCurl(self::domainSandboxME, $endpoint, null, $header);
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

        $response = $this->PostRequestCurl(self::domainME, $endpoint, $header, $fields,'URL');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }

    public function trackShipment(Request $request) {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $domain = 'https://sandbox.melhorenvio.com.br';
        $endpoint = '/api/v2/me/shipment/tracking';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token
        );
        $fields = array(
            'orders[]' => $request->request->get('orders[]')
        );
        $response = $this->PostRequestCurl($domain, $endpoint, $header, $fields, 'JSON');
        $output = $response['output'];
        $resultCode = $response['resultCode'];
        return response($output, $resultCode);
    }

    public function getAgency(Request $request, $id='') {
        if (!ctype_digit($id)) {
            return response('ID da loja não informado', 400);
        }
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $domain = 'https://sandbox.melhorenvio.com.br';
        $endpoint = '/api/v2/me/shipment/agencies/' . $id;
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token
        );
        $response = $this->GetDelRequestCurl($domain, $endpoint,'' , $header, 'JSON');
        $output = $response['output'];
        $resultCode = $response['resultCode'];
        return response($output, $resultCode);
    }

    public function listarFiltros(Request $request) {
        $params =  $request->query;
        $country = "BR";
        $token = $request->bearerToken();
        $curl = curl_init();
        $domain = 'https://sandbox.melhorenvio.com.br';
        $endpoint = '/api/v2/me/shipment/agencies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: ' . $token
        );
        $query = '?country=' . $country;
        if ($params->has("company")) {
            $query = $query . "&company=" . $params->get("company");
        }
        if ($params->has("state")) {
            $query = $query . "&state=" . $params->get("uf");
        }
        if ($params->has("city")) {
            $query = $query . "&city=" . $params->get("cidade");
        }

        curl_setopt_array($curl,[
            CURLOPT_URL => $domain . $endpoint . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
        ]);

        $output = trim(curl_exec($curl));
        curl_close($curl);
        return response($output, 200);
    }

    public function getService(Request $request, $id='') {
        if (!ctype_digit($id)) {
            return response('ID do serviço não informado', 400);
        }
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $domain = 'https://sandbox.melhorenvio.com.br';
        $endpoint = '/api/v2/me/shipment/services/' . $id;
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token
        );
        $response = $this->GetDelRequestCurl($domain, $endpoint,'' , $header, 'JSON');
        $output = $response['output'];
        $resultCode = $response['resultCode'];
        return response($output, $resultCode);
    }
}

