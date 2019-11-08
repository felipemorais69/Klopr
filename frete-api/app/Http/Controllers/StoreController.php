<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class StoreController extends Controller
{

    public function listStores(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'

        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/companies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);
        $query='';

        $response = $this->GetDelRequestCurl($domain, $endpoint, $query, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }

    public function detailStore(Request $request, $id)
    {
        if (!is_int($id)) {
            return response('ID da loja informado incorretamente', 400);
        }

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'

        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/companies/';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);
        $query = '?id_loja=' . $id;

        $response = $this->GetDelRequestCurl($domain, $endpoint, $query, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }

    public function savePhone(Request $request, $id='') {
        if (!ctype_digit($id)) {
            return response('ID da loja não informado', 400);
        }
        $token = $request->bearerToken();
        $curl = curl_init();
        $domain = 'https://sandbox.melhorenvio.com.br';
        $endpoint = '/api/v2/me/companies/' . $id . '/phones';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);
        $query = '';
        curl_setopt_array($curl,[
            CURLOPT_URL => $domain . $endpoint . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $request->all(),
            CURLOPT_POST => 1,
        ]);
        $output = trim(curl_exec($curl));
        curl_close($curl);
        return response($output, 200);
    }
}
