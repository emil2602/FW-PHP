<?php

namespace Fw\PhpFw\Http\Middleware;

use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        StartSession::class,
        Authenticate::class,
        RouterDispatch::class
    ];

    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            return new Response('Server error', 500);
        }

        $middlewareClass = array_shift($this->middleware);

        $middlewareGet = $this->container->get($middlewareClass);

        return $middlewareGet->process($request, $this);
    }
}