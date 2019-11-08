<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{

    public function registerUser(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken(); // Formato: 'Bearer {token}'
        $domain = 'https://sandbox.melhorenvio.com.br'; // ALTERAR!!!
        $endpoint = '/api/v2/me/companies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);

        $fields = array(
            'firstname' => $request->request->get('firstname'), //Obrigatório
            'lastname' => $request->request->get('lastname'), //Obrigatório
            'document' => $request->request->get('document'), //Obrigatório
            'birthdate' => $request->request->get('birthdate'), //Obrigatório
            'email' => $request->request->get('email'), //Obrigatório
            'password' => $request->request->get('password'), //Obrigatório
            'phone_mobile' => $request->request->get('mobile-phone'), //Obrigatório
            'phone_fixed' => $request->request->get('fixed-phone'), //Opcional
            'company' => $request->request->get('company'), //Opcional
            'coupon' => $request->request->get('coupon'), //Opcional
            'terms' => $request->request->get('terms'), //Obrigatório
            'address[label]' => $request->request->get('address-label'), //Opcional
            'address[postal_code]' => $request->request->get('postal-code'), //Obrigatório
            'address[address]' => $request->request->get('address'), //Obrigatório
            'address[number]' => $request->request->get('address-number'), //Obrigatório
            'address[complement]' => $request->request->get('address-complement'), //Opcional
            'address[district]' => $request->request->get('district'),  //Opcional
            'address[city]' => $request->request->get('city'), //Obrigatório
            'address[state_abbr]' => $request->request->get('state-abbr'), //Obrigatório
            'address[country]' => $request->request->get('country'), //Opcional
        );

        $response = $this->PostRequestCurl($domain, $endpoint, $header, $fields, 'JSON');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
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
