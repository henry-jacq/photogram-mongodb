<?php

namespace App\Core;

use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    public function __construct(
        private readonly View $view,
        private readonly Config $config
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

    /**
     * Write response as JSON
     */
    public function respondAsJson(Response $response, array $payload, int $statusCode = 200)
    {
        $response->getBody()->write(packJson($payload));   
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}