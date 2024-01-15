<?php

namespace App\Middleware;

use App\Model\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class AuthoriseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly User $user,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (empty($_SESSION['user'])) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', '/login');
        } else {
            $user = $this->user->getUser();
            $request = $request->withAttribute('userData', $user);
        }

        return $handler->handle($request);
    }
}
