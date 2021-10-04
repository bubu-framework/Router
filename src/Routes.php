<?php
namespace Bubu\Router;

class Routes
{
    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }
}