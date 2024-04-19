<?php

use Fw\PhpFw\Http\Kernel;
use League\Container\Container;
use Fw\PhpFw\Routing\Router;
use Fw\PhpFw\Routing\RouterInterface;
use League\Container\ReflectionContainer;

$routes = include BASE_PATH . '/routes/web.php';

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)->addMethodCall('registerRoutes', [$routes]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);


return $container;