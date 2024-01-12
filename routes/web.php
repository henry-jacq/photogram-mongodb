<?php

use Slim\App;
use App\Middleware\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;
use App\Controller\Api\PostController;
use App\Controller\Api\AuthController;
use App\Controller\User\HomeController;
use App\Controller\User\ProfileController;

return function (App $app) {
    // User Routes
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [HomeController::class, 'indexView']);
        $group->get('discover', [HomeController::class, 'discoverView']);
        $group->get('profile/{name}', [ProfileController::class, 'profileView']);
    });

    // Auth Routes
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('login', [AuthController::class, 'loginView']);
        $group->post('login', [AuthController::class, 'verifyLogin']);
        $group->get('register', [AuthController::class, 'registerView']);
        $group->post('register', [AuthController::class, 'createUser']);
        $group->get('forgot-password', [AuthController::class, 'forgotPassword']);
    })->add(AuthMiddleware::class);

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->post('/post/create', [PostController::class, 'createPost']);
        $group->post('/post/delete', [PostController::class, 'deletePost']);
        $group->post('/post/update', [PostController::class, 'updatePost']);
        $group->post('/post/count', [PostController::class, 'getCount']);
    });
};
