<?php

use Slim\App;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use Slim\Routing\RouteCollectorProxy;


return function (App $app) {
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [HomeController::class, 'indexView']);
        $group->get('discover', [HomeController::class, 'discoverView']);
    });
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('login', [AuthController::class, 'login']);
        $group->post('login', [AuthController::class, 'verifyLogin']);
        $group->get('register', [AuthController::class, 'register']);
        $group->post('register', [AuthController::class, 'createUser']);
        $group->get('forgot-password', [AuthController::class, 'forgotPassword']);
    })->add(AuthMiddleware::class);
};