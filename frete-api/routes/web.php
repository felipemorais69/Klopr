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

//  GET - Autenticação
$router->get('oauth/authorize?client_id={{client_id}}&redirect_uri={{callback}}&response_type=code&scope=cart-read','AutenticationController@autentication');

//  Token Solicitation
$router->post('oauth/token','AutenticationController@tokenSolicitation');

//  Refresh Token
$router->post('oauth/token','AutenticationController@refreshToken');
//  POST - Geração de Etiquetas

$router->post('shipment/generate', 'EtiquetaController@generationEtiquetas'); //Gera as Etiquetas

//  GET - Search de Etiquetas

$router->get('orders/search?q=%7Bid%7Ctracking%7Cauthorization_code%7Cself_tracking%7D&status=%7Bpending%7Creleased%7Cposted%7Cdelivered%7Ccanceled%7Cundelivered%7D', 'EtiquetaController@searchEtiquetas');

//  GET - List Pedidos

$router->get('orders', 'UserController@listPedidos');

//  GET - List Transportadoras, List Transportadoras Information, List Services.
$router->get('shipment/companies','TransportController@listTransport');
$router->get('shipment/companies/{{id_transportadora}}','TransportController@listInfoTransport');
$router->get('shipment/services','TransportController@listServices');

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
