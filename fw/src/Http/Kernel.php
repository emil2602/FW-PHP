<?php

namespace Fw\PhpFw\Http;

use Doctrine\DBAL\Connection;
use Fw\PhpFw\Http\Middleware\RequestHandlerInterface;
use Fw\PhpFw\Routing\RouterInterface;
use League\Container\Container;

class Kernel
{

    public function __construct(
        private RouterInterface $router,
        private Container $container,
        private RequestHandlerInterface $requestHandler
    ){}

    public function handle(Request $request): Response
    {
        try {
            $response = $this->requestHandler->handle($request);


        } catch (\Throwable $exception) {
            $response = new Response($exception->getMessage(), 500);
        }

        return  $response;
    }
}