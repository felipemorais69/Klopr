<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    const domainME = 'https://www.melhorenvio.com.br';
    const domainSandboxME = 'https://sandbox.melhorenvio.com.br';


    protected function PostRequestCurl($domain, $endpoint, $header, $fields, $method='JSON')
    {
        $cHandle = curl_init();

        if ($method == 'JSON') {
            $payload = json_encode($fields);
        } else { // URL form
            $urlString = '';
            foreach($fields as $key => $value) {
                $urlString .= $key.'='.$value.'&'; }
            $payload = rtrim($urlString, '&');
        }

        curl_setopt_array($cHandle,[
            CURLOPT_URL => $domain . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POST, true,
            CURLOPT_POSTFIELDS, $payload
        ]);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        $response = array(
            'resultCode' => $resultCode,
            'output' => $output
        );
        return $response;
    }


    protected function GetDelRequestCurl($domain, $endpoint, $query, $header='')
    {
        $cHandle = curl_init();

        curl_setopt_array($cHandle,[
            CURLOPT_URL => $domain . $endpoint . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header
        ]);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        $response = array(
            'resultCode' => $resultCode,
            'output' => $output
        );

        return $response;
    }

}
