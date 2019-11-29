<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{

    public function registerUser(Request $request)
    {
        /* INPUT
         * --form "firstname=João" \
           --form "lastname=Silva" \
           --form "document=99988877732" \
           --form "birthdate=1945-01-05" \
           --form "email=email@domain.com" \
           --form "password=password" \
           --form "phone_mobile=5398783214" \
           --form "phone_fixed=5333333333" \
           --form "company=Nome da loja" \
           --form "coupon=MELHORLOJA" \
           --form "terms=1" \
           --form "address_label=Meu Endereco" \
           --form "address_postal_code=96020000" \
           --form "address_address=Rua General Osório" \
           --form "address_number=596" \
           --form "address_complement=" \
           --form "address_district=Centro" \
           --form "address_city=Pelotas" \
           --form "address_state_abbr=RS" \
           --form "address_country=BR"
         */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/register';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $fields = array(
            'firstname' => $request->input('firstname'), //Obrigatório -- Primeiro nome
            'lastname' => $request->input('lastname'), //Obrigatório -- Sobrenome
            'document' => $request->input('document'), //Obrigatório -- CPF
            'birthdate' => $request->input('birthdate'), //Obrigatório -- Data de nascimento
            'email' => $request->input('email'), //Obrigatório -- email
            'password' => $request->input('password'), //Obrigatório -- senha
            'phone_mobile' => $request->input('phone_mobile'), //Obrigatório -- Celular
            'phone_fixed' => $request->input('phone_fixed'), //Opcional -- Telefone fixo
            'company' => $request->input('company'), //Opcional -- Nome da loja
            'coupon' => $request->input('coupon'), //Opcional -- CUPOM
            'terms' => $request->input('terms'), //Obrigatório -- Aceitação de termos do uso
            'address[label]' => $request->input('address_label'), //Opcional -- Identificação do endereço
            'address[postal_code]' => $request->input('address_postal_code'), //Obrigatório -- CEP
            'address[address]' => $request->input('address_address'), //Obrigatório -- Rua/Logradouro
            'address[number]' => $request->input('address_number'), //Obrigatório -- Número do imóvel
            'address[complement]' => $request->input('address_complement'), //Opcional -- Complemento do endereço
            'address[district]' => $request->input('address_district'),  //Opcional -- Bairro
            'address[city]' => $request->input('address_city'), //Obrigatório -- Cidade
            'address[state_abbr]' => $request->input('address_state_abbr'), //Obrigatório -- Sigla do Estado
            'address[country]' => $request->input('address_country'), //Opcional -- País
        );

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields, 'URL');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function addCredit(Request $request)
    {
        /*
         * --form "gateway=mercado-pago" \
           --form "redirect=url" \
           --form "value=10.50"
         */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/balance';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $fields = array(
            'gateway' => $request->input('gateway'), //Obrigatório -- moip / mercado-pago
            'redirect' => $request->input('redirect'), //Opcional -- URL de redirecionamento
            'value' => $request->input('value'), //Obrigatório -- Valor monetário
        );

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields, 'JSON');
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


    public function userInfo(Request $request) {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function userBalance(Request $request) {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/balance?pretty';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }

    public function userAddresses(Request $request) {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/addresses';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}
