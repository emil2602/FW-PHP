<?php

namespace Fw\PhpFw\Routing;


use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Fw\PhpFw\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{

    public function dispatch(Request $request)
    {

        [$handler, $vars] = $this->extractRouteInfo($request);

        if(is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        }

        return [$handler, $vars];
    }

    private function extractRouteInfo(Request $request)
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
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