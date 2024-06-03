<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Post;
use App\Model\User;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly Post $post,
        private readonly User $user
    ) {
        parent::__construct($view);
    }

    public function home(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Home',
            'user' => $userData,
            'posts' => $this->post->getAllPosts(),
            'avatar' => $this->user->getUserAvatar($userData)
        ];
        return $this->render($response, 'user/home', $args);
    }

    public function discover(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Discover',
            'user' => $userData,
            'avatar' => $this->user->getUserAvatar($userData),
        ];
        return $this->render($response, 'user/discover', $args);
    }

    public function subscribe(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Photogram Pro'
        ];

        return $this->render($response, 'user/subscribe', $args);
    }

    public function files(Request $request, Response $response): Response
    {
        $category = $request->getAttribute('category');
        $imageName = $request->getAttribute('image');

        $path = STORAGE_PATH . '/' . $category . '/' . $imageName;

        if (in_array($category, ['posts', 'avatars'])) {
            if ($category == 'posts') {
                $image = $this->post->getImage($imageName);
            }
            if ($category == 'avatars') {
                $imgPath = $category . DIRECTORY_SEPARATOR . $imageName;
                $image = $this->user->getAvatar($imgPath);
            }
            
            if (!$image) {
                return $response->withStatus(404);
            }
        } else {
            return $response->withStatus(404);
        }

        $response->getBody()->write($image);

        return $response
        ->withHeader('Content-Type', mime_content_type($path))
        ->withHeader('Content-Length', filesize($path))
        ->withHeader('Cache-Control', 'max-age=' . (60 * 60 * 24 * 365))
        ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 365))
        ->withHeader('Last-Modified', gmdate(DATE_RFC1123, filemtime($path)))
        ->withHeader('Pragma', '');
    }
}
