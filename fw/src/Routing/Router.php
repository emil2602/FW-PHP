<?php

namespace Fw\PhpFw\Routing;


use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Fw\PhpFw\Controller\AbstractController;
use Fw\PhpFw\Http\Request;
use League\Container\Container;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{

    private array $routes = [];

    public function dispatch(Request $request, Container $container)
    {

        [$handler, $vars] = $this->extractRouteInfo($request);

        if(is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);

            if (is_subclass_of($controller, AbstractController::class)) {
                $controller->setRequest($request);
            }

            $handler = [$controller, $method];
        }

        return [$handler, $vars];
    }

    public function registerRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    private function extractRouteInfo(Request $request)
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {

            foreach ($this->routes as $route) {
                $routeCollector->addRoute(...$route);
            }

        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );

        switch ($routeInfo[0]){
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $message = "$allowedMethods method is not supported";
                throw new \Exception($message);
            default:
                throw new \Exception('Route not found');
        }
    }

}