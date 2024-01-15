<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Config;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly View $view
    ) {
        parent::__construct($view);
    }

    public function profile(Request $request, Response $response): Response
    {
        $name = $request->getAttribute('name');
        $args = [
            'name' => $name,
            'title' => ucfirst($name) . "'s Profile",
            'user' => $request->getAttribute('userData')
        ];
        return $this->render($response, 'user/profile', $args);
    }

    public function edit(Request $request, Response $response): Response
    {
        $args = [
            'title' => "Edit Profile",
            'user' => $request->getAttribute('userData')
        ];
        return $this->render($response, 'user/edit', $args);
    }
}
