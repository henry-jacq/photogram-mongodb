<?php

use Slim\App;
use App\Core\Auth;
use App\Core\View;
use App\Model\Post;
use App\Model\User;
use MongoDB\Client;
use App\Core\Config;
use App\Model\Image;
use App\Core\MongoDB;
use App\Core\Request;
use App\Core\Session;
use function DI\create;
use Slim\Factory\AppFactory;
use App\Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $middleware = require CONFIG_PATH . DIRECTORY_SEPARATOR . '/middleware.php';
        $router     = require ROUTES_PATH . DIRECTORY_SEPARATOR . 'web.php';

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
    Image::class => function () {
        if (extension_loaded('gd')) {
            return new Image();
        }
        throw new Exception('GD Extension is not loaded.');
    },
    User::class => function(ContainerInterface $container) {
        return new User(
            $container->get(Image::class),
            $container->get(MongoDB::class)
        );
    },
    Auth::class => function (ContainerInterface $container) {
        return new Auth(
            $container->get(User::class),
            $container->get(Session::class)
        );
    },
    ZipArchive::class => function() {
        return new ZipArchive();
    },
    Post::class => function (ContainerInterface $container) {
        return new Post(
            $container->get(MongoDB::class),
            $container->get(User::class),
            $container->get(ZipArchive::class)
        );
    }
];