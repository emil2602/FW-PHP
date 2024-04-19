<?php

namespace Fw\PhpFw\Routing;

use Fw\PhpFw\Http\Request;
use League\Container\Container;

interface RouterInterface
{
    public function dispatch(Request $request, Container $container);
    public function registerRoutes(array $routes);
}