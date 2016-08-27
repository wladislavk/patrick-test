<?php
require __DIR__ . '/vendor/autoload.php';
require 'parameters.php';
require 'routes.php';

use Doctrine\ORM\Tools\Setup;
use Components\Router;
use Components\Response;

$doctrinePaths = [
    __DIR__ . "/doctrine",
];

$doctrineConfig = Setup::createAnnotationMetadataConfiguration($doctrinePaths, $parameters['isDevMode']);

$templateDir = __DIR__ . '/web/views';

foreach (glob($templateDir . '/*.phtml') as $file) {
    include_once $file;
}

try {
    $route = Router::getRoute($routes);
    $controller = new $route['controller']($parameters, $doctrineConfig);
    /** @var Response $response */
    $response = $route['controller']->$route['function']();
    http_response_code($response->getCode());
    echo $response->getData();
} catch (Exception $e) {
    echo "Exception caught:\n" . $e->getMessage() . "\n" . $e->getTraceAsString();
}
