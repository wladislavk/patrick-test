<?php
namespace Components;

use Decorators\ServerDecorator;

class Router
{
    /**
     * @var ServerDecorator
     */
    private $serverDecorator;

    public function __construct(ServerDecorator $serverDecorator)
    {
        $this->serverDecorator = $serverDecorator;
    }

    public function getRoute(array $routes)
    {
        $currentRouteFunction = '';
        foreach ($routes as $routeUrl => $routeFunction) {
            if ($routeUrl == $this->serverDecorator->getRequestUri()) {
                $currentRouteFunction = $routeFunction;
                break;
            }
        }
        if (!$currentRouteFunction) {
            throw new \RuntimeException('No route matched');
        }
        $splitRoute = explode('/', $currentRouteFunction);
        if (sizeof($splitRoute) != 2) {
            throw new \RuntimeException('Route definition should be in form "controller/action"');
        }
        $controllerClass = 'Controllers\\' . $splitRoute[0];
        $functionName = $splitRoute[1];
        $route = [
            'controller' => $controllerClass,
            'function' => $functionName,
        ];
        return $route;
    }
}
