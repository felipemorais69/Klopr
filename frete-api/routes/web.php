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

$router->group(['prefix' => 'api/v1/frete'], function () use ($router) {
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

    $router->get('app-settings', 'AppController@showAppSettings'); // Configurações da aplicação
    $router->get('cart', 'CartController@listItems'); // Listar itens do carrinho
    $router->get('cart/{id}', 'CartController@detailItem'); // Detalhar item do carrinho
    $router->get('shipment/agencies', 'ShipmentController@listAgencies'); // Listar agências
    $router->get('shipment/checkout-all', 'ShipmentController@buyAllShipping'); // Finalizar a compra dos envios
    $router->get('stores', 'StoreController@listStores'); // Listagem de lojas
    $router->get('stores/{id}', 'StoreController@detailStore'); // Informações da loja(id)

    $router->delete('cart/{id}', 'CartController@delItem'); // Remover item do carrinho(id)

    $router->post('cart/add', 'CartController@insertShipping'); // Adicionar fretes no carrinho | BODY - RAW |
    $router->post('shipment/cancel', 'ShipmentController@cancelShipment'); // Cancela remessa | BODY - FORMDATA | (se possível)
    $router->post('shipment/print', 'ShipmentController@printTag'); // Imprimir etiqueta de envio | BODY - RAW |
    $router->post('shipment/checkout', 'ShipmentController@buyShipping'); // Compra de fretes (Checkout) (Ordens) | BODY - FORMDATA |
    $router->post('stores/register', 'StoreController@registerStore'); // Cadastro de loja | BODY - FORMDATA |
    $router->post('user/register', 'UserController@registerUser'); // Cadastro de usuário | BODY - FORMDATA |
    $router->post('user/add-credit', 'UserController@addCredit'); // Adição de crédito | BODY - FORMDATA |

    $router->post('store/{$id}/phones', 'StoreController@savePhone'); // Salvar telefone da loja 
    $router->get('shipment/agencies/{id}', 'ShipmentController@getAgency'); // Consultar agencia
    $router->get('shipment/services/{id}', 'ShipmentController@getService'); // Consultar servico
    $router->get('shipment/agenciesFilter', 'ShipmentController@listarFiltros'); // Consultar agencia
    $router->get('shipment/buy-shipping', 'ShipmentController@buyShipping'); // Finalizar a compra dos envios
    $router->post('shipment/tracking', 'ShipmentController@trackShipment'); // Finalizar a compra dos envios
    $router->post('shipment/preview', 'ShipmentController@preview'); // Pré visualização de etiquetas
    $router->get('shipment/cancellable/{id}', 'ShipmentController@cancellable'); // Checar cancelavel

    $router->get('user', 'UserController@userInfo');
    $router->get('user/balance', 'UserController@userBalance');
    $router->get('user/addresses', 'UserController@userAddresses');

    $router->post('shipment/calculate/product', 'ShipmentController@calculateProductShipment');
    $router->post('shipment/calculate/package', 'ShipmentController@calculatePackageShipment');
    $router->post('stores/add-address/{id}', 'StoreController@registerAddress');
    $router->post('stores/add-picture/{id}', 'StoreController@addPicture');


});
