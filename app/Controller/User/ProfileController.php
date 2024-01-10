<?php

namespace App\Controller\User;

use App\Core\View;
use App\Core\Config;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly Config $config
    ) {
        parent::__construct($view, $config);
    }

    public function profileView(Request $request, Response $response): Response
    {
        $name = $request->getAttribute('name');
        $args = [
            'title' => ucfirst($name) . "'s Profile",
            'name' => $name
        ];
        return $this->render($response, 'user/profile', $args);
    }
}
