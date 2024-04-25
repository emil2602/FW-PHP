<?php

namespace Fw\PhpFw\Http;

use Doctrine\DBAL\Connection;
use Fw\PhpFw\Routing\RouterInterface;
use League\Container\Container;
use function FastRoute\simpleDispatcher;

class Kernel
{

    public function __construct(
        private RouterInterface $router,
        private Container $container
    ){}

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

            $response = call_user_func_array($routeHandler, $vars);
        } catch (\Throwable $exception) {
            $response = new Response($exception->getMessage(), 500);
        }

        return  $response;
    }
}