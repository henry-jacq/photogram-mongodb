<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Post;
use App\Model\User;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly Post $post,
        private readonly User $user
    ) {
        parent::__construct($view);
    }

    public function profile(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');
        $name = strtolower($request->getAttribute('name'));
        $profile = $this->user->exists([
            'username' => $name, 'email' => null
        ]);

        if ($profile) {
            $args = [
                'name' => $name,
                'user' => $userData,
                'profileUser' => $profile,
                'title' => ucfirst($name) . "'s Profile",
                'posts' => $this->post->getPostsById($profile['_id'])
            ];
            return $this->render($response, 'user/profile', $args);
        }
        
        return $this->renderErrorPage($response);
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
