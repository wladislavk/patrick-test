<?php
namespace Services;

use Doctrine\ORM\EntityManager;
use Entities\Message;

class OldMessageRemover
{
    private $entityManager;

    const MAX_NUMBER_OF_MESSAGES = 10;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function remove()
    {
        $messages = $this->entityManager->getRepository('Entities\Message')
            ->findBy([], ['createdAt' => 'DESC']);
        if (!$messages) {
            return;
        }
        /** @var Message[] $oldMessages */
        $oldMessages = array_slice($messages, self::MAX_NUMBER_OF_MESSAGES);
        foreach ($oldMessages as $message) {
            $this->entityManager->remove($message);
        }
        $this->entityManager->flush();
    }
}
