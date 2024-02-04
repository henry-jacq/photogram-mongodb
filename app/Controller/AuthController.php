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
        private readonly Auth $auth
    )
    {
        parent::__construct($view);
    }

    public function landingView(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Share Memories'
        ];
        return $this->render($response, 'auth/landing', $args, false, true);
    }

    public function onboardView(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Onboarding'
        ];
        return $this->render($response, 'auth/onboarding', $args, false, false);
    }
    
    public function loginView(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Login',
            'auth_css' => true
        ];
        return $this->render($response, 'auth/login', $args, false, false);
    }

    public function registerView(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Register',
            'auth_css' => true
        ];
        return $this->render($response, 'auth/register', $args, false, false);
    }

    public function forgotPassword(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Forgot password',
            'auth_css' => true
        ];
        return $this->render($response, 'auth/reset_password', $args, false, false);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}