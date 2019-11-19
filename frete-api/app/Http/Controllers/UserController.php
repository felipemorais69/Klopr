<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{

    public function ___(Request $request)
    {   /*
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'

        $cHandle = curl_init();
        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/companies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);
        //$query = $request->query();

        curl_setopt_array($cHandle,[
            CURLOPT_URL => $domain . $endpoint; // . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            //CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            //CURLOPT_FOLLOWLOCATION => false,
            //CURLOPT_TIMEOUT => 5,
        ]);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        return response($output, $resultCode);*/
    }

    public function ____(Request $request, $id='')
    {   /*
        if (!is_int($id)) {
            return response('ID da loja não informado', 400);
        }

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'

        $cHandle = curl_init();
        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/companies/';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);
        $query = '?id_loja=' . $id;

        curl_setopt_array($cHandle,[
            CURLOPT_URL => $domain . $endpoint . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            //CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            //CURLOPT_FOLLOWLOCATION => false,
            //CURLOPT_TIMEOUT => 5,
        ]);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        return response($output, $resultCode);*/
    }

    public function listPedidos(Request $request)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "{{url}}/api/v2/me/orders",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-type: application/json",
            "Accept: application/json",
            "Authorization: Bearer {{token}}"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }
    }
}
