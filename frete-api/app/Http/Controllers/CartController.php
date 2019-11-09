<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class CartController extends Controller
{

    public function delItem(Request $request)
    {

        /*
        HEADERS
        Accept: application/json
        Content-Type: application/json
        Authorization : Bearer {{token}}
        */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'
        $endpoint = '/api/v2/me/cart/{id}';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetDelRequestCurl(self::domainME, $endpoint, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}
