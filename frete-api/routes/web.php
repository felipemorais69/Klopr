<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Http\Response;

//GET - Autenticação
$router->get('/oauth/authorize?client_id={{client_id}}&redirect_uri={{callback}}&response_type=code&scope=cart-read', function () use ($router) {
    return response()->json();
});

//POST - Solicitação de Token

$app->post('/oauth/token/', function (Request $request) {
    ->header("application/json");
    $this->validate($request, [
        'grant_type' => 'authorization_code',
        'client_id' => '{{client_id}}',
        'client_secret' => '{{client_secret}}',
        'redirect_uri' => '{{callback}}',
        'code' => '{{code}}'
    ]);
});

//POST - Refresh Token

$app->post('/oauth/token/', function (Request $request) {
    ->header("application/json");
    $this->validate($request, [
        'grant_type' => 'refresh_token',
        'refresh_token' => '{{refresh_token}}',
        'client_id' => '{{client_id}}',
        'client_secret' => '{{client_secret}}',
        'scope' => 'cart-read+cart-write+companies-read+companies-write+coupons-read+coupons-write+notifications-read+orders-read+products-read+products-write+purchases-read+shipping-calculate+shipping-cancel+shipping-checkout+shipping-companies+shipping-generate+shipping-preview+shipping-print+shipping-share+shipping-tracking+ecommerce-shipping+transactions-read+users-read+users-write+webhooks-read+webhooks-write'
    ]);
});



////// GETs -- ASRF //////
$router->group(['prefix' => 'api/v1/frete'], function () use ($router) {
    $router->get('app-settings', 'AppController@showAppSettings'); // Configurações da aplicação
    $router->get('stores', 'StoreController@listStores'); // Listagem de lojas
    $router->get('store/{id}', 'StoreController@detailStore'); // Informações da loja(id)
    $router->get('shipment/agencies', 'ShippmentController@listAgencies'); // Listar agências
    $router->get('shipment/buy-shipping', 'ShippmentController@buyShipping'); // Finalizar a compra dos envios

    $router->del('cart/{id}', 'CartController@delItem'); // Remover item do carrinho(id)

    $router->post('shipment/cancel', 'ShipmentController@cancelShipment'); // Cancela remessa (se possível)
});

//CALCULO DO FRETE DE UM PACOTE

$app->postPacote('api/v2/me/shipment/calculate', function (Request $request) {
    ->header("application/json");

    $url = ''; // ENDPOINT ONDE ESTARÁ O JSON DA KLOPR (Seguindo o padrão https://docs.melhorenvio.com.br/shipment/calculate.html)
    $json = file_get_contents($url);
    $json_decoded = json_decode($json);

    $fromPostalCode = $json_decoded->from[0];
    $fromAddress = $json_decoded->from[1];
    $fromNumber = $json_decoded->from[2];

    $toPostalCode = $json_decoded->to[0];
    $toAddress = $json_decoded->to[1];
    $toNumber = $json_decoded->to[2];

    $packageWeight = $json_decoded->package[0];
    $packageWidth = $json_decoded->package[1];
    $packageHeight = $json_decoded->package[2];
    $packageLenght = $json_decoded->package[3];

    $insuranceValue = $json_decoded->options[0];
    $receipt = $json_decoded->options[1];
    $ownHand = $json_decoded->options[2];
    $collect = $json_decoded->options[3];

    $services = $json_decoded->services;


    $this->validate($request, [
        'from[postal_code]' => $fromPostalCode,
        'from[address]' => $fromAddress,
        'from[number]' => $fromNumber,
        'to[postal_code]' => $toPostalCode,
        'to[address]' => $toAddress,
        'to[number]' => $toNumber,
        'package[weight]' => $packageWeight,
        'package[width]' => $packageWidth,
        'package[height]' => $packageHeight,
        'package[length]' => $packageLenght,
        'options[insurance_value]' => $insuranceValue,
        'options[receipt]' => $receipt,
        'options[collect]' => $collect,
        'options[own_hand]' => $ownHand,
        'services' => $services

    ]);


});

//POST PRODUTO

$app->postProdutos('api/v2/me/shipment/calculate', function (Request $request) {
    ->header("application/json");

    $url = ''; // ENDPOINT ONDE ESTARÁ O JSON DA KLOPR (Seguindo o padrão https://docs.melhorenvio.com.br/shipment/calculate.html)
    $json = file_get_contents($url);
    $json_decoded = json_decode($json);

    $fromPostalCode = $json_decoded->from[0];
    $fromAddress = $json_decoded->from[1];
    $fromNumber = $json_decoded->from[2];

    $toPostalCode = $json_decoded->to[0];
    $toAddress = $json_decoded->to[1];
    $toNumber = $json_decoded->to[2];

    $receipt = $json_decoded->options[1];
    $ownHand = $json_decoded->options[2];
    $collect = $json_decoded->options[3];

    $services = $json_decoded->services;

    $range = count($json_decoded->products);

    for ($i = 0; $i < $range; $i++){

        $productId = $json_decoded->products[$i][0]; //OPCIONAL
        $productWeight = $json_decoded->products[$i][1];
        $productWidth = $json_decoded->products[$i][2];
        $productHeight = $json_decoded->products[$i][3];
        $productLenght = $json_decoded->products[$i][4];
        $productQuantity = $json_decoded->products[$i][5]; //OPCIONAL, DEFAULT = 1
        $productIV = $json_decoded->products[$i][6]; //INSURANCE VALUE


        $this->validate($request, [
            'from[postal_code]' => $fromPostalCode,
            'from[address]' => $fromAddress,
            'from[number]' => $fromNumber,
            'to[postal_code]' => $toPostalCode,
            'to[address]' => $toAddress,
            'to[number]' => $toNumber,
            'options[receipt]' => $receipt,
            'options[collect]' => $collect,
            'options[own_hand]' => $ownHand,
            'services' => $services,
            'product[id]' => $productId,
            'product[weight]' => $productWeight,
            'product[width]' => $productWidth,
            'product[height]' => $productHeight,
            'product[length]' => $productLenght,
            'product[quantity]' => $productQuantity,
            'product[insurance_value]' => $productIV

        ]);
    }

});

// POST IMAGEM DA LOJA

$app->postImagemLoja('/api/v2/me/companies/{id_loja}/picture', function (Request $request) {
    ->header("application/json");

    $this->validate($request, [
        'file' => '', //ENDPOINT COM A IMAGEM DA LOJA

    ]);
});

//POST ENDEREÇO DA LOJA

$app->postEndereco('/api/v2/me/companies/762566a8-ec38-4d51-8feb-941ed0367efb/addresses', function (Request $request) {
    ->header("application/json");

    $url = ''; // ENDPOINT ONDE ESTARÁ O JSON DA KLOPR (Seguindo o padrão https://docs.melhorenvio.com.br/users/addresses.html)
    $json = file_get_contents($url);
    $data = json_decode($json);

    $postalCode = $data->data[2];
    $address = $data->data[3];
    $number = $data->data[4];
    $city = $data->data->city[1];
    $state = $data->data->city->state[2];


    $this->validate($request, [
        'postal_code' => $postalCode,
        'address' => $address,
        'number' => $number,
        'state' => $state,
        'city' => $city

    ]);
});

