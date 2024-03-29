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

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, null, $header);
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

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, $query, $header);
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
        $endpoint = '/api/v2/me/companies';
        $header = array(
            'Accept: application/json',
            'Content-type: application/x-www-form-urlencoded',
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


    public function registerAddress(Request $request, $id)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/companies/' . $id . '/addresses';
        $header = array(
            'Accept: application/json',
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $token);

        $fields = $request->all();

        $response = $this->PostRequestCurl(self::domainSandboxME, $endpoint, $header, $fields, 'URL');
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function addPicture(Request $request, $id)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/companies/' . $id . '/picture';
        $header = array(
            'Accept: application/json',
            'Content-type: multipart/form-data',
            'Authorization: Bearer ' . $token);

        $file = $request->file('file')->getRealPath();

        $response = $this->PostFileCurl(self::domainSandboxME, $endpoint, $header, $file);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }

    public function savePhone(Request $request, $id='') {
        if (!ctype_digit($id)) {
            return response('ID da loja não informado', 400);
        }
        $token = $request->bearerToken();
        $curl = curl_init();
        $domain = 'https://sandbox.melhorenvio.com.br';
        $endpoint = '/api/v2/me/companies/' . $id . '/phones';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: ' . $token);
        $query = '';
        curl_setopt_array($curl,[
            CURLOPT_URL => $domain . $endpoint . $query,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $request->all(),
            CURLOPT_POST => 1,
        ]);
        $output = trim(curl_exec($curl));
        curl_close($curl);
        return response($output, 200);
    }

}

