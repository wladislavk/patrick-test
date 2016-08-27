<?php
namespace Tests\Components;

use Components\Router;
use Decorators\ServerDecorator;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    private $routes = [
        '/foo' => 'FooCtrl/foo',
        '/wrong' => 'BarCtrl',
    ];

    private $currentRoute;

    private $serverDecorator;

    public function testGetRoute()
    {
        $this->currentRoute = '/foo';
        $this->mockServerDecorator();
        $router = new Router($this->serverDecorator);
        $routeParams = [
            'controller' => 'Controllers\FooCtrl',
            'function' => 'foo',
        ];
        $this->assertEquals($routeParams, $router->getRoute($this->routes));
    }

    public function testGetUndefinedRoute()
    {
        $this->currentRoute = '/nonexistent';
        $this->mockServerDecorator();
        $router = new Router($this->serverDecorator);
        $this->setExpectedException(\RuntimeException::class, 'No route matched');
        $router->getRoute($this->routes);
    }

    public function testGetWronglyDefinedRoute()
    {
        $this->currentRoute = '/wrong';
        $this->mockServerDecorator();
        $router = new Router($this->serverDecorator);
        $message = 'Route definition should be in form "controller/action"';
        $this->setExpectedException(\RuntimeException::class, $message);
        $router->getRoute($this->routes);
    }

    private function mockServerDecorator()
    {
        $this->serverDecorator = $this
            ->getMockBuilder(ServerDecorator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->serverDecorator->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnCallback([$this, 'getRequestUriCallback']));
    }

    public function getRequestUriCallback()
    {
        return $this->currentRoute;
    }
}
