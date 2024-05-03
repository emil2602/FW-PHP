<?php

namespace Fw\PhpFw\Http\Middleware;

use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;

class Authenticate implements MiddlewareInterface
{

    private bool $isAuth = true;
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if(!$this->isAuth) {
            return new Response('Auth failed', 401);
        }

        return $handler->handle($request);
    }
}