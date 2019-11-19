<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class EtiquetaController extends Controller
{
    public function generationEtiquetas(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "{{url}}/api/v2/me/shipment/generate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('orders[0]' => 'af3fef55-b068-4a43-8d9e-cfcda148a38c'),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Content-Type: application/json",
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

    public function searchEtiquetas(Request $request)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "{{url}}/api/v2/me/orders/search?q=%7Bid%7Ctracking%7Cauthorization_code%7Cself_tracking%7D&status=%7Bpending%7Creleased%7Cposted%7Cdelivered%7Ccanceled%7Cundelivered%7D",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Content-type: application/json",
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
} ?>