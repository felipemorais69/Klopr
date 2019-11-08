<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AppController extends Controller
{

    public function showAppSettings(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'

        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/shipment/app-settings';
        $header = array(
            'Accept: application/json',
            'Authorization: ' . $token);

        $response = $this->GetDelRequestCurl($domain, $endpoint, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}
