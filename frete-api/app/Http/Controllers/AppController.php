<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AppController extends Controller
{

    public function showAppSettings(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();

        $endpoint = '/api/v2/me/shipment/app-settings';
        $header = array(
            'Accept: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetDelRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}
