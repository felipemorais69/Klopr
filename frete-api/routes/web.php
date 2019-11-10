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

/*
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
});*/


////// GETs -- ASRF //////
$router->group(['prefix' => 'api/v1/frete'], function () use ($router) {
    $router->get('app-settings', 'AppController@showAppSettings'); // Configurações da aplicação
    $router->get('stores', 'StoreController@listStores'); // Listagem de lojas
    $router->get('stores/{id}', 'StoreController@detailStore'); // Informações da loja(id)
    $router->get('shipment/agencies', 'ShipmentController@listAgencies'); // Listar agências
    $router->get('shipment/buy-shipping', 'ShipmentController@buyShipping'); // Finalizar a compra dos envios
    $router->get('cart', 'CartController@listItems'); // Listar itens do carrinho ** TEST
    $router->get('cart/{id}', 'CartController@detailItems'); // Detalhar item do carrinho ** TEST

    $router->delete('cart/{id}', 'CartController@delItem'); // Remover item do carrinho(id)

    $router->post('shipment/cancel', 'ShipmentController@cancelShipment'); // Cancela remessa (se possível)
    $router->post('user/register', 'UserController@registerUser'); // Cadastro de usuário * TEST
    $router->post('user/add-credit', 'UserController@addCredit'); // Adição de crédito ** TEST
    $router->post('stores/register', 'UserController@registerStore'); // Cadastro de loja ** TEST

});

