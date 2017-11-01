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
    $app->api->get('products/:id', [$app->controller, 'getProduct']);
    $app->api->get('products', [$app->controller, 'getProducts']);
    $app->api->post('products', [$app->controller, 'addProduct']);
    $app->api->put('products/:id', [$app->controller, 'editProduct']);
    $app->api->delete('products/:id', [$app->controller, 'deleteProduct']);

    $app->api->get('types/:id', [$app->controller, 'getProduct']);
    $app->api->get('types', [$app->controller, 'getProducts']);
    $app->api->post('types', [$app->controller, 'addProduct']);
    $app->api->put('types/:id', [$app->controller, 'editProduct']);
    $app->api->delete('types/:id', [$app->controller, 'deleteProduct']);

    $app->run();
