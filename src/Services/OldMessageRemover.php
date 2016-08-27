<?php
namespace Services;

use Doctrine\ORM\EntityManager;
use Entities\Message;

class OldMessageRemover
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function remove()
    {
        $query = "SELECT m FROM Entity\Message m ORDER BY m.createdAt DESC";
        $result = $this->entityManager->createQuery($query)->getArrayResult();
        /** @var Message[] $oldMessages */
        $oldMessages = array_slice($result, 10);
        foreach ($oldMessages as $message) {
            $this->entityManager->remove($message);
        }
        $this->entityManager->flush();
    }
}
