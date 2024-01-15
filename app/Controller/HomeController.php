<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly View $view
    ) {
        parent::__construct($view);
    }
    
    public function home(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Home'
        ];
        return $this->render($response, 'user/home', $args);
    }

    public function discover(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Discover'
        ];
        return $this->render($response, 'user/discover', $args);
    }
}