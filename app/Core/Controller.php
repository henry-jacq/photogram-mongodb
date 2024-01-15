<?php

namespace App\Core;

use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    public function __construct(
        private readonly View $view
    )
    {
    }
    
    public function render(Response $response, string $viewPath, array $args, $withFrame = true)
    {
        $response->getBody()->write(
            (string) $this->view
            ->createPage($viewPath, $args, $withFrame)
            ->render()
        );
        return $response;
    }
}