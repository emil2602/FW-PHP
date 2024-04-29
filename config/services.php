<?php

use Doctrine\DBAL\Connection;
use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Dbal\ConnectionFactory;
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

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

$container->addShared('twig-loader', FilesystemLoader::class)
    ->addArgument(new StringArgument($viewsPath));

$container->addShared('twig', Environment::class)->addArgument('twig-loader');

$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)->addArgument($connectionParams);

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(\Fw\PhpFw\Console\Application::class)->addArgument($container);

$container->add(\Fw\PhpFw\Console\Kernel::class)->addArgument($container)->addArgument(\Fw\PhpFw\Console\Application::class);

$container->add('migrate', \Fw\PhpFw\Console\Commands\MigrateCommand::class)->addArgument(Connection::class)->addArgument(new StringArgument(dirname(__DIR__). '/database/migrations'));

return $container;