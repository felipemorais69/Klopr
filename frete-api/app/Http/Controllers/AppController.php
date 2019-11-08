<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AppController extends Controller
{

    public function showAppSettings(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'

        $cHandle = curl_init();
        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/shipment/app-settings';
        $header = array(
            'Accept: application/json',
            'Authorization: ' . $token);

        curl_setopt_array($cHandle, [
            CURLOPT_URL => $domain . $endpoint/* . $query*/,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
        ]);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        return response($output, $resultCode);
    }
}
