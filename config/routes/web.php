<?php

use Slim\App;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use Slim\Routing\RouteCollectorProxy;


return function (App $app) {
    $app->get('/', [HomeController::class, 'index']);
    $app->get('/login', [AuthController::class, 'login']);
    $app->get('/register', [AuthController::class, 'register']);
    // $app->group('/', function (RouteCollectorProxy $group) {
    //     $group->get('', [HomeController::class, 'index']);
    // });
    // $app->group('/', function (RouteCollectorProxy $group) {
    //     $group->get('login', [AuthController::class, 'login']);
    //     $group->post('login', [AuthController::class, 'verifyLogin']);
    //     $group->get('register', [AuthController::class, 'register']);
    //     $group->post('register', [AuthController::class, 'createUser']);
    //     $group->get('forgot-password', [AuthController::class, 'forgotPassword']);
    // })->add(AuthMiddleware::class);
};