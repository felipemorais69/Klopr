<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class StoreController extends Controller
{

    public function listStores(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/companies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetDelRequestCurl(self::domainME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }

    public function detailStore(Request $request, $id)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/companies/';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $query = $id;

        $response = $this->GetDelRequestCurl(self::domainME, $endpoint, $query, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }

    public function registerStore(Request $request)
    {
        /*INPUT
         * --form "name=Lojinha Melhor Envio" \
           --form "email=contato@lojinhamelhorenvio.com" \
           --form "company_name=Lojinha da Melhor Envio" \
           --form "document=89473900000160"
         */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/balance';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $fields = array(
            'name' => $request->input('name'), //Obrigatório -- Nome de identificação no sistema
            'email' => $request->input('email'), //Obrigatório -- email da loja
            'picture' => $request->input('picture'), //Opcional -- Imagem
            'description' => $request->input('description'), //Opcional -- Descrição da loja
            'company_name' => $request->input('company_name'), //Obrigatório -- Nome da loja
            'document' => $request->input('document'), //Obrigatório -- CNPJ da loja
            'state_register' => $request->input('state_register'), //Opcional
        );

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields, 'URL');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}
