<?php
namespace Controllers;

use Entities\Message;
use Services\OldMessageRemover;
use Components\Response;

class ApiController extends BaseController
{
    public function get()
    {
        $messages = $this->entityManager->getRepository('Entities\Message')
            ->findBy([], ['createdAt' => 'DESC']);

        if (!$messages) {
            $messages = [];
        }
        $jsonData = [
            'messages' => $messages,
        ];
        return new Response(json_encode($jsonData));
    }

    public function send()
    {
        $oldMessageRemover = new OldMessageRemover($this->entityManager);
        $postdata = json_decode(file_get_contents("php://input"), true);
        if (!isset($postdata['message'])) {
            $jsonData = [
                'error' => 'Message cannot be empty',
            ];
            return new Response(json_encode($jsonData), 422);
        }
        $messageText = $postdata['message'];
        $message = new Message();
        $message->setMessage($messageText);
        $message->setCreatedAt(new \DateTime());
        try {
            $this->entityManager->persist($message);
            $this->entityManager->flush();
            $oldMessageRemover->remove();
        } catch (\Exception $e) {
            $jsonData = [
                'error' => $e->getMessage(),
            ];
            return new Response(json_encode($jsonData), 422);
        }
        $jsonData = [
            'result' => 'success',
        ];
        return new Response(json_encode($jsonData));
    }
}
