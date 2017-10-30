<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 20:36
     */

    require __DIR__ . '/../bootstrap.php';

    use CatalogAPI\App;

    // try{
        $app = new App($config);
    // }catch (\PDOException $exception){
    //     return new \CatalogAPI\ErrorResponse($exception->getCode(), [],$exception->getMessage());
    // }

    $app->api->get('/:id', [$app->controller, 'getProduct']);
    $app->api->get('', [$app->controller, 'getProducts']);
    $app->api->post('', [$app->controller, 'addProduct']);
    $app->api->put('/:id', [$app->controller, 'editProduct']);
    $app->api->delete('/:id', [$app->controller, 'deleteProduct']);
    $app->run();