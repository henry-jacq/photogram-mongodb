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
    // Prevent user routes access for not logged in users
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('home', [HomeController::class, 'home']);
        $group->get('discover', [HomeController::class, 'discover']);
        $group->get('subscribe', [HomeController::class, 'subscribe']);
        $group->get('profile/edit', [ProfileController::class, 'edit']);
        $group->get('profile/{name}', [ProfileController::class, 'profile']);
        $group->get('files/{category}/{image}', [HomeController::class, 'files']);
    })->add(AuthoriseMiddleware::class);

    // Auth Routes
    // Prevent auth routes access for logged in users
    $app->group('/', function (RouteCollectorProxy $group) {
        $group->get('', [AuthController::class, 'landingView']);
        $group->get('onboarding', [AuthController::class, 'onboardView']);
        $group->get('login', [AuthController::class, 'loginView']);
        $group->get('register', [AuthController::class, 'registerView']);
        $group->get('forgot-password', [AuthController::class, 'forgotPassword']);
    })->add(AuthMiddleware::class);

    $app->get('/logout', [AuthController::class, 'logout']);

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}[/{params:.*}]', [ApiController::class, 'process']);
    })->add(AuthoriseMiddleware::class);
};
