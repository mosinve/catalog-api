<?php
    /**
     * Created by PhpStorm.
     * User: MosinVE
     * Date: 25.10.2017
     * Time: 20:36
     */

    require __DIR__ . '../bootstrap.php';

    use CatalogAPI\App;
use CatalogAPI\CorsMiddleware;

$app = new App($config);
    $app->api->get('/', [$app->controller, 'getProducts'])->middleware([CorsMiddleware::class]);
    $app->api->get(':id', [$app->controller, 'getProduct'])->middleware([CorsMiddleware::class]);
    $app->api->post('/', [$app->controller, 'addProduct'])->middleware([CorsMiddleware::class]);
    $app->api->put(':id', [$app->controller, 'editProduct'])->middleware([CorsMiddleware::class]);
    $app->api->delete(':id', [$app->controller, 'deleteProduct'])->middleware([CorsMiddleware::class]);
    $app->run();
