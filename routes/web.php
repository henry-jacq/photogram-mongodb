<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Controller\ApiController;
use App\Middleware\AuthMiddleware;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\ProfileController;
use App\Middleware\AuthoriseMiddleware;

return function (App $app) {
    // User Routes
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [HomeController::class, 'indexView']);
        $group->get('discover', [HomeController::class, 'discoverView']);
        $group->get('profile/{name}', [ProfileController::class, 'profileView']);
    })->add(AuthoriseMiddleware::class);

    // Auth Routes
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [AuthController::class, 'landingView']);
        $group->get('login', [AuthController::class, 'loginView']);
        $group->get('register', [AuthController::class, 'registerView']);
        $group->get('forgot-password', [AuthController::class, 'forgotPassword']);
    })->add(AuthMiddleware::class);

    $app->get('/logout', [AuthController::class, 'logout']);
    
    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}', [ApiController::class, 'process']);
    });
};
