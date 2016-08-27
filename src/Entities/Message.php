<?php
namespace Entities;

class Message
{
    // cannot be private in order to be encoded to JSON
    public $id;
    public $message;
    public $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
