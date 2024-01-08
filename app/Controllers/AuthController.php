<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Core\Config;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly Config $config,
        private readonly Auth $auth
    )
    {
        parent::__construct($view, $config);
    }
    
    public function login(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Login'
        ];
        return $this->render($response, 'auth/login', $args, false);
    }

    public function register(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Register'
        ];
        return $this->render($response, 'auth/register', $args, false);
    }

    public function createUser(Request $request, Response $response, array $args): Response
    {
        $result = $this->auth->register($request->getParsedBody());
        return $this->respondAsJson($response, ['message' => boolval($result)]);
    }
    
    public function verifyLogin(Request $request, Response $response): Response
    {
        $result = $this->auth->login($request->getParsedBody());
        $data = ['message' => (boolval($result)) ? true : false];
        return $this->respondAsJson($response, $data);
        // return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function forgotPassword(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Forgot password',
            'name' => 'Henry'
        ];
        return $this->render($response, 'auth/forgot-password', $args, false);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}