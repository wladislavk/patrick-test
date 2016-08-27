<?php
namespace Components;

class Router
{
    public static function getRoute(array $routes)
    {
        $currentRouteFunction = '';
        foreach ($routes as $routeUrl => $routeFunction) {
            if ($routeUrl == $_SERVER['REQUEST_URI']) {
                $currentRouteFunction = $routeFunction;
                break;
            }
        }
        if (!$currentRouteFunction) {
            throw new \RuntimeException('No route matched');
        }
        $splitRoute = explode('/', $currentRouteFunction);
        $controllerClass = 'Controllers\\' . $splitRoute[0];
        $controller = new $controllerClass();
        $functionName = $splitRoute[1];
        $route = [
            'controller' => $controller,
            'function' => $functionName,
        ];
        return $route;
    }
}
