<?php
namespace Controllers;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

abstract class BaseController
{
    protected $parameters;
    protected $entityManager;

    public function __construct(array $parameters, Configuration $doctrineConfig)
    {
        $this->parameters = $parameters;
        $connectionParams = [
            'driver' => $this->parameters['db_driver'],
            'path' => $this->parameters['db_path'],
        ];
        $connection = DriverManager::getConnection($connectionParams);
        $this->entityManager = EntityManager::create($connection, $doctrineConfig);
    }
}
