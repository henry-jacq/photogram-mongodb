<?php

use Slim\App;
use App\Core\View;
use App\Core\Config;
use function DI\create;
use Slim\Factory\AppFactory;
use Psr\Container\ContainerInterface;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $middleware = require CONFIG_PATH . '/middleware.php';
        $router     = require CONFIG_PATH . '/routes/web.php';

        $app = AppFactory::create();

        $router($app);

        $middleware($app);

        return $app;
    },
    Config::class => create(Config::class)->constructor(
        require CONFIG_PATH . '/app.php'
    ),
    View::class => function(ContainerInterface $container){
        return new View($container->get(Config::class));
    },
];