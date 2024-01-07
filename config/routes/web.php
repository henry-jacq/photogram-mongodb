<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Controllers\HomeController;


return function (App $app) {
    $app->get('/', [HomeController::class, 'index']);
};