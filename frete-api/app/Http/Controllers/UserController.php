<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class UserController extends Controller
{

    public function registerUser(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/register';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $fields = array(
            'firstname' => $request->request->get('firstname'), //Obrigatório -- Primeiro nome
            'lastname' => $request->request->get('lastname'), //Obrigatório -- Sobrenome
            'document' => $request->request->get('document'), //Obrigatório -- CPF
            'birthdate' => $request->request->get('birthdate'), //Obrigatório -- Data de nascimento
            'email' => $request->request->get('email'), //Obrigatório -- email
            'password' => $request->request->get('password'), //Obrigatório -- senha
            'phone_mobile' => $request->request->get('phone_mobile'), //Obrigatório -- Celular
            'phone_fixed' => $request->request->get('phone_fixed'), //Opcional -- Telefone fixo
            'company' => $request->request->get('company'), //Opcional -- Nome da loja
            'coupon' => $request->request->get('coupon'), //Opcional -- CUPOM
            'terms' => $request->request->get('terms'), //Obrigatório -- Aceitação de termos do uso
            'address[label]' => $request->request->get('address[label]'), //Opcional -- Identificação do endereço
            'address[postal_code]' => $request->request->get('address[postal_code]'), //Obrigatório -- CEP
            'address[address]' => $request->request->get('address[address]'), //Obrigatório -- Rua/Logradouro
            'address[number]' => $request->request->get('address[number]'), //Obrigatório -- Número do imóvel
            'address[complement]' => $request->request->get('address[complement]'), //Opcional -- Complemento do endereço
            'address[district]' => $request->request->get('address[district]'),  //Opcional -- Bairro
            'address[city]' => $request->request->get('address[city]'), //Obrigatório -- Cidade
            'address[state_abbr]' => $request->request->get('address[state_abbr]'), //Obrigatório -- Sigla do Estado
            'address[country]' => $request->request->get('address[country]'), //Opcional -- País
        );

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields, 'JSON');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function addCredit(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/balance';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $fields = array(
            'gateway' => $request->request->get('gateway'), //Obrigatório -- moip / mercado-pago
            'redirect' => $request->request->get('redirect'), //Opcional -- URL de redirecionamento
            'value' => $request->request->get('value'), //Obrigatório -- Valor monetário
        );

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields, 'JSON');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}
