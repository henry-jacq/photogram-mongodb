<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Config;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly Config $config
    ) {
        parent::__construct($view, $config);
    }
    
    public function index(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Home'
        ];
        return $this->render($response, 'home/home', $args);
    }
}