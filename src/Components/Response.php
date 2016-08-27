<?php
namespace Components;

class Response
{
    private $code;
    private $data;

    public function __construct($data, $code = 200)
    {
        $this->code = $code;
        $this->data = $data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getData()
    {
        return $this->data;
    }
}
