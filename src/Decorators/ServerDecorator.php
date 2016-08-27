<?php
namespace Decorators;

class ServerDecorator
{
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
