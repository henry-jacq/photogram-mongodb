<?php

namespace App\Controller;

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
        private readonly Auth $auth,
        private readonly Config $config
    )
    {
        parent::__construct($view, $config);
    }

    public function landingView(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Share Memories'
        ];
        return $this->render($response, 'auth/landing', $args, false);
    }
    
    public function loginView(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Login'
        ];
        return $this->render($response, 'auth/login', $args, false);
    }

    public function registerView(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Register'
        ];
        return $this->render($response, 'auth/register', $args, false);
    }

    public function forgotPassword(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Forgot password',
            'name' => 'Henry'
        ];
        return $this->render($response, 'auth/reset_password', $args, false);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}