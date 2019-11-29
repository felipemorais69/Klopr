<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class CartController extends Controller
{

    public function delItem(Request $request)
    {
        if (!$request->isMethod('delete'))
        {
            return response('Wrong method', 400);
        }

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/cart/{id}';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetDelRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function listItems(Request $request)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/cart';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);

        $response = $this->GetRequestCurl(self::domainSandboxME, $endpoint, null, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function detailItem(Request $request, $id)
    {
        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/cart';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $query = '/' . $id;

        $response = $this->GetDelRequestCurl(self::domainSandboxME, $endpoint, $query, $header);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }


    public function insertShipping(Request $request)
    {
        /*
         * INPUT (RAW)
         *{
            "service": 3,
            "agency": 100,
            "from": {
              "name": "Nome do remetente",
              "phone": "53984470102",
              "email": "contato@melhorenvio.com.br",
              "document": "16571478358",
              "company_document": "89794131000100",
              "state_register": "123456",
              "address": "Endereço do remetente",
              "complement": "Complemento",
              "number": "1",
              "district": "Bairro",
              "city": "São Paulo",
              "country_id": "BR",
              "postal_code": "01002001",
              "note": "observação"
            },
            "to": {
              "name": "Nome do destinatário",
              "phone": "53984470102",
              "email": "contato@melhorenvio.com.br",
              "document": "16571478358",
              "company_document": "89794131000100",
              "state_register": "123456",
              "address": "Endereço do destinatário",
              "complement": "Complemento",
              "number": "2",
              "district": "Bairro",
              "city": "Porto Alegre",
              "state_abbr": "RS",
              "country_id": "BR",
              "postal_code": "90570020",
              "note": "observação"
            },
            "products": [
              {
                "name": "Papel adesivo para etiquetas",
                "quantity": 3,
                "unitary_value": 4.50,
                "weight": 1
              }
            ],
            "package": {
              "weight": 1,
              "width": 12,
              "height": 4,
              "length": 17
            },
            "options": {
              "insurance_value": 20.50,
              "receipt": false,
              "own_hand": false,
              "collect": false,
              "reverse": false,
              "non_commercial": true,
              "invoice": {
                "key": "31190307586261000184550010000092481404848162"
              }
            }
        }
         */

        $requestUrl = $request->fullUrl();
        $token = $request->bearerToken();
        $endpoint = '/api/v2/me/cart';
        $header = array(
            'Accept: application/json',
            'Content-type: application/json',
            'Authorization: Bearer ' . $token);
        $body = $request->getContent();

        $response = $this->PostRequestCurl(self::domainME, $endpoint, $header, $body, null);
        $output = $response['output'];
        $resultCode = $response['resultCode'];

        return response($output, $resultCode);
    }
}
