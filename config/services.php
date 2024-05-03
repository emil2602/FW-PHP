<?php

use Doctrine\DBAL\Connection;
use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Dbal\ConnectionFactory;
use Fw\PhpFw\Http\Kernel;
use Fw\PhpFw\Http\Middleware\RequestHandler;
use Fw\PhpFw\Http\Middleware\RequestHandlerInterface;
use Fw\PhpFw\Http\Middleware\RouterDispatch;
use Fw\PhpFw\Session\Session;
use Fw\PhpFw\Session\SessionInterface;
use Fw\PhpFw\Template\TwigFactory;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use Fw\PhpFw\Routing\Router;
use Fw\PhpFw\Routing\RouterInterface;
use League\Container\ReflectionContainer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$routes = include BASE_PATH . '/routes/web.php';
$viewsPath = BASE_PATH . '/views';
$connectionParams = [
    'dbname' => 'fw',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add('framework-cmd-namespace', new StringArgument('Fw\\PhpFw\\Console\\Commands\\'));

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)->addMethodCall('registerRoutes', [$routes]);

$container->add(RequestHandlerInterface::class, RequestHandler::class)->addArgument($container);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container)
    ->addArgument(RequestHandlerInterface::class)
;

$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
          ->addArguments([
              new StringArgument($viewsPath),
              SessionInterface::class,
          ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});

$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)->addArgument($connectionParams);

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(\Fw\PhpFw\Console\Application::class)->addArgument($container);

$container->add(\Fw\PhpFw\Console\Kernel::class)->addArgument($container)->addArgument(\Fw\PhpFw\Console\Application::class);

$container->add('migrate', \Fw\PhpFw\Console\Commands\MigrateCommand::class)->addArgument(Connection::class)->addArgument(new StringArgument(dirname(__DIR__). '/database/migrations'));

$container->add(RouterDispatch::class)->addArguments([
    RouterInterface::class,
    $container
]);

return $container;