<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 20:36
     */

    require __DIR__ . '/../bootstrap.php';

    use CatalogAPI\App;

    $app = new App($config);
    $app->api->get('products/:id', [$app->productsController, 'getProduct']);
    $app->api->get('products', [$app->productsController, 'getProducts']);
    $app->api->post('products', [$app->productsController, 'addProduct']);
    $app->api->put('products/:id', [$app->productsController, 'editProduct']);
    $app->api->delete('products/:id', [$app->productsController, 'deleteProduct']);

    // $app->api->get('types/:id', [$app->controller, 'getProduct']);
    // $app->api->get('types', [$app->controller, 'getProducts']);
    // $app->api->post('types', [$app->controller, 'addProduct']);
    // $app->api->put('types/:id', [$app->controller, 'editProduct']);
    // $app->api->delete('types/:id', [$app->controller, 'deleteProduct']);

    $app->run();
