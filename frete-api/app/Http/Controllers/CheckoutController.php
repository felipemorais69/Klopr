<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CheckoutController extends Controller
{

    public function buyShipping(Request $request)
    {
        /*

        */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'
        $endpoint = '/api/v2/me/shipment/checkout';
        $header = array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetDelRequestCurl(self::domainME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}

