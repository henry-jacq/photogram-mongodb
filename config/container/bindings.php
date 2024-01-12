<?php

use Slim\App;
use App\Core\View;
use MongoDB\Client;
use App\Core\Config;
use App\Core\MongoDB;
use App\Core\Request;
use App\Core\Session;
use function DI\create;
use Slim\Factory\AppFactory;
use App\Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;
use App\Interfaces\DatabaseConnectorInterface;
use App\Model\User;
use Psr\Http\Message\ResponseFactoryInterface;

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
    SessionInterface::class => function (ContainerInterface $container) {
        return new Session($container->get(Config::class));
    },
    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),
    Request::class => function(ContainerInterface $container) {
        return new Request($container->get(SessionInterface::class));
    },
    MongoDB::class => function(ContainerInterface $container) {
        $config = $container->get(Config::class)->get('db');
        $client = new Client($config['host']);
        return MongoDB::getInstance($client, $config['dbname']);
    },
    User::class => function(ContainerInterface $container) {
        return new User($container->get(MongoDB::class));
    }
];