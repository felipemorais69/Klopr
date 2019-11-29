<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    const domainME = 'https://www.melhorenvio.com.br';
    const domainSandboxME = 'https://sandbox.melhorenvio.com.br';


    protected function PostRequestCurl($domain, $endpoint, $header, $fields, $data='JSON')
    {
        $cHandle = curl_init();

        if ($data == 'JSON') {
            $payload = json_encode($fields);
        } elseif ($data == 'URL') { // URL form
            $urlString = '';
            foreach($fields as $key => $value) {
                $urlString .= $key.'='.$value.'&'; }
            $payload = rtrim($urlString, '&');
        } else {
            $payload = $fields;
        }

        curl_setopt($cHandle, CURLOPT_URL, $domain . $endpoint);
        curl_setopt($cHandle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cHandle, CURLOPT_POST, true);
        curl_setopt($cHandle, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($cHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cHandle, CURLOPT_HEADER, false);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        return array(
            'resultCode' => $resultCode,
            'output' => $output
        );
    }


    protected function GetDelRequestCurl($domain, $endpoint, $query, $header='')
    {
        $cHandle = curl_init();

        curl_setopt($cHandle, CURLOPT_URL, $domain . $endpoint . $query);
        curl_setopt($cHandle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cHandle, CURLOPT_RETURNTRANSFER, true);

        $output = trim(curl_exec($cHandle));
        $resultCode = curl_getinfo($cHandle, CURLINFO_HTTP_CODE);
        curl_close($cHandle);

        return array(
            'resultCode' => $resultCode,
            'output' => $output
        );
    }

}
