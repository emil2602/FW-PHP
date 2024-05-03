<?php

namespace Fw\PhpFw\Http\Middleware;

use Fw\PhpFw\Http\Request;
use Fw\PhpFw\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}