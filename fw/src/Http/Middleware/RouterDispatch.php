<?php

namespace Fw\PhpFw\Http\Middleware;

use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;
use Fw\PhpFw\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatch implements MiddlewareInterface
{

    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response = call_user_func_array($routeHandler, $vars);

            return $response;
    }
}