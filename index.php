<?php

use Framework\Http\Response\ExceptionResponse;
use Framework\Routing\Router;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$routes = include  __DIR__ . DIRECTORY_SEPARATOR .'config/routes.php';

$router = new Router();
$router->setRoutes($routes);

try {
    $router->handle($_SERVER['REQUEST_URI']);
} catch (\Throwable $e) {
    ExceptionResponse::send($e->getMessage());
}
