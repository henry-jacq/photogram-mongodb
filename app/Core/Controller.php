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

    public function renderErrorPage(Response $response)
    {
        $response->getBody()->write(
            (string) $this->view
                ->createPage('error', ['code' => 404], false)
                ->render()
        );
        return $response->withStatus(404);
    }
    
    public function render(Response $response, string $viewPath, array $args, $header = true, $footer = true)
    {
        $args['header'] = $header;
        $args['footer'] = $footer;
        $response->getBody()->write(
            (string) $this->view
            ->createPage($viewPath, $args)
            ->render()
        );
        return $response;
    }
}