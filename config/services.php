<?php

use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\Kernel;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use Fw\PhpFw\Routing\Router;
use Fw\PhpFw\Routing\RouterInterface;
use League\Container\ReflectionContainer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$routes = include BASE_PATH . '/routes/web.php';
$viewsPath = BASE_PATH . '/views';

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)->addMethodCall('registerRoutes', [$routes]);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->addShared('twig-loader', FilesystemLoader::class)
    ->addArgument(new StringArgument($viewsPath));

$container->addShared('twig', Environment::class)->addArgument('twig-loader');

$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

return $container;