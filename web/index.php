<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../parameters.php';
require __DIR__ . '/../parameters_test.php';
require __DIR__ . '/../routes.php';

use Doctrine\ORM\Tools\Setup;
use Components\Router;
use Components\Response;
use Controllers\BaseController;
use Decorators\ServerDecorator;

// uncomment this line for integration tests
//$parameters = $parameters_test;

$doctrinePaths = [
    __DIR__ . "/../doctrine",
];

$doctrineConfig = Setup::createXMLMetadataConfiguration($doctrinePaths, $parameters['isDevMode']);

$templateDir = __DIR__ . '/views';

foreach (glob($templateDir . '/*.phtml') as $file) {
    include_once $file;
}

try {
    $router = new Router(new ServerDecorator());
    $route = $router->getRoute($routes);
    /** @var BaseController $controller */
    $controller = new $route['controller']($parameters, $doctrineConfig);
    /** @var Response $response */
    $response = $controller->$route['function']();
    http_response_code($response->getCode());
    echo $response->getData();
} catch (Exception $e) {
    echo "<div>Exception caught:<br>" . $e->getMessage() . "<br>" . $e->getTraceAsString() . "</div>";
}
