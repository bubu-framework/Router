<?php
namespace Bubu\Router;

use Bubu\Router\Exception\RouterException;

class Router
{
    private array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'PATCH'  => [],
        'DELETE' => []
    ];

    private array $named = [];

    private function register(string $route, mixed $callable, string $method, ?string $name)
    {
        $this->routes[$method][$route] = new Routes($route, $callable);
        if (!is_null($name)) $this->named[] = $route;
    }

    public function get(string $route, mixed $callable, ?string $name)
    {
        $this->register($route, $callable, 'GET', $name);
    }

    public function post(string $route, mixed $callable, ?string $name)
    {
        $this->register($route, $callable, 'POST', $name);
    }

    public function put(string $route, mixed $callable, ?string $name)
    {
        $this->register($route, $callable, 'PUT', $name);
    }

    public function patch(string $route, mixed $callable, ?string $name)
    {
        $this->register($route, $callable, 'PATCH', $name);
    }

    public function delete(string $route, mixed $callable, ?string $name)
    {
        $this->register($route, $callable, 'DELETE', $name);
    }

    public function multiple(string $methods, string $route, mixed $callable, ?string $name)
    {
        foreach (explode('|', $methods) as $method) {
            $this->register($route, $callable, strtoupper($method), $name);
        }
    }

    public function match()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) throw new RouterException('REQUEST_METHOD available');
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

    }

    public function getNamedRoute(string $name)
    {
        
    }
}