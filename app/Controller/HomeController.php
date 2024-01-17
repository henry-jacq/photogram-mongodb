<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use App\Model\Post;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly Post $post
    ) {
        parent::__construct($view);
    }

    public function home(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Home',
            'user' => $request->getAttribute('userData'),
            'posts' => $this->post->getAllPosts()
        ];
        return $this->render($response, 'user/home', $args);
    }

    public function discover(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Discover',
            'user' => $request->getAttribute('userData')
        ];
        return $this->render($response, 'user/discover', $args);
    }

    public function files(Request $request, Response $response): Response
    {
        $category = $request->getAttribute('category');
        $imageName = $request->getAttribute('image');

        $path = '/' . $category . '/' . $imageName;

        if ($category == 'posts') {
            $image = $this->post->getImage($imageName);
            if (!$image) {
                return $response->withStatus(404);
            }
        } else {
            return $response->withStatus(404);
        }

        $response->getBody()->write($image);

        return $response
        ->withHeader('Content-Type', mime_content_type(STORAGE_PATH . $path))
        ->withHeader('Content-Length', filesize(STORAGE_PATH . $path))
        ->withHeader('Cache-Control', 'max-age='. (60 * 60 * 24 * 365))
        ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 365))
        ->withHeader('Last-Modified', gmdate(DATE_RFC1123, filemtime(STORAGE_PATH . $path)))
        ->withoutHeader('Pragma');
    }
}
