<?php
namespace Bubu\Router;

class Router
{
    private array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'PATCH'  => [],
        'DELETE' => []
    ];

    private function register(string $route, mixed $callable, string $method, ?string $name)
    {
        $this->routes[$method][] = new Routes($route, $callable);
    }

    public function get(string $route, mixed $callable, ?string $name)
    {
        $this->register($route, $callable, 'GET', $name);
    }

    public function match()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }
}