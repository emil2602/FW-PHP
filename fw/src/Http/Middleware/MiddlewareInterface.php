<?php

namespace Fw\PhpFw\Http\Middleware;

use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}