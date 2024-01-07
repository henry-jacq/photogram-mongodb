<?php

use Slim\App;
use App\Core\View;
use MongoDB\Client;
use App\Core\Config;
use App\Core\Request;
use App\Core\Session;
use function DI\create;
use App\Database\Database;
use Slim\Factory\AppFactory;
use App\Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;
use App\Database\Connectors\MySQLConnector;
use App\Database\Connectors\MongoDBConnector;
use App\Interfaces\DatabaseConnectorInterface;
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
    DatabaseConnectorInterface::class =>
    function (ContainerInterface $container) {
        $config = $container->get(Config::class)->get('db');
        $pdo = new \PDO(
            "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}",
            $config['user'],
            $config['pass'],
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        return new MySQLConnector($container->get(Config::class));
    },
    Database::class => function(ContainerInterface $container) {
        return Database::getInstance($container->get(DatabaseConnectorInterface::class));
    },
];