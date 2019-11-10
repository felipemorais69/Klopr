<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ShipmentController extends Controller
{

    public function listAgencies(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'

        $cHandle = curl_init();
        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/shipment/agencies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);

        curl_setopt_array($cHandle,[
            CURLOPT_URL => $domain . $endpoint,
            CURLOPT_RETURNTRANSFER => true
            //CURLOPT_HTTPHEADER => $header,
        ]);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        return response($output, $resultCode);
    }

    public function cancelShipment(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'
        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/companies/';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);

        $fields = array(
            'order[id]' => $request->request->get('order-id'),
            'order[reason_id]' => $request->request->get('reason-id'),
            'order[description]' => $request->request->get('description')
            );

        $response = $this->PostRequestCurl($domain, $endpoint, $header, $fields, 'JSON');
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
            'orders' => $request->request->get('orders')
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

    public function preview(Request $request) {
        $token = $request->bearerToken();
        $domain = 'https://melhorenvio.com.br';
        $endpoint = '/api/v2/me/shipment/preview';
        $header = array(
            'Accept: application/json',
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: ' . $token
        );
        $fields = array(
            'orders' => $request->request->get('orders')
        );
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => $domain . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_POST => true,
        ]);
        $output = trim(curl_exec($curl));
        curl_close($curl);
        return response($output, 200);
    }

    public function cancellable(Request $request, $id='') {
        $curl = curl_init();
        $token = $request->bearerToken();
        $domain = 'https://melhorenvio.com.br';
        $endpoint = '/api/v2/me/shipment/cancellable';
        $order_id = $request->request->get('orders');
        curl_setopt_array($curl, array(
            CURLOPT_URL => $domain . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS =>"{\n  \"orders\": {\n    \"" . $id . "\"\n  }\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: " . $token
            ),
        ));
        $response = trim(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);
        return response($response, 200);
    }
}

