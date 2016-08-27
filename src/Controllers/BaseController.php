<?php
namespace Controllers;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

abstract class BaseController
{
    protected $parameters;
    protected $entityManager;

    public function __construct(array $parameters, Configuration $doctrineConfig)
    {
        $this->parameters = $parameters;
        $this->entityManager = EntityManager::create($parameters['db_url'], $doctrineConfig);
    }
}
