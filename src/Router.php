<?php
namespace Bubu\Router;

use Bubu\Router\Exception\RouterException;

class Router
{
    public static array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'PATCH'  => [],
        'DELETE' => []
    ];

    private string $controllerNamespace = '';
    private string $controllerSuffix    = '';

    private static array $named = [];

    private static function register(string $route, mixed $callable, string $method, ?string $name): void
    {
        self::$routes[$method][$route] = new Routes($route, $callable);
        if (!is_null($name)) self::$named[] = $route;
    }

    public static function get(string $route, mixed $callable, ?string $name = null): void
    {
        self::register($route, $callable, 'GET', $name);
    }

    public static function post(string $route, mixed $callable, ?string $name = null): void
    {
        self::register($route, $callable, 'POST', $name);
    }

    public static function put(string $route, mixed $callable, ?string $name = null): void
    {
        self::register($route, $callable, 'PUT', $name);
    }

    public static function patch(string $route, mixed $callable, ?string $name = null): void
    {
        self::register($route, $callable, 'PATCH', $name);
    }

    public static function delete(string $route, mixed $callable, ?string $name = null): void
    {
        self::register($route, $callable, 'DELETE', $name);
    }

    public static function multiple(string $methods, string $route, mixed $callable, ?string $name = null): void
    {
        foreach (explode('|', $methods) as $method) {
            self::register($route, $callable, strtoupper($method), $name);
        }
    }

    public static function controllerNamespace(string $namespace): void
    {
        self::$controllerNamespace = $namespace;
    }

    public static function getControllerNamespace(): string
    {
        return self::$controllerNamespace;
    }

    public static function controllerSuffix(string $suffix): void
    {
        self::$controllerSuffix = $suffix;
    }

    public static function getControllerSuffix(): string
    {
        return self::$controllerSuffix;
    }

    public static function getNamedRoute(string $name): string
    {
        if (!isset(self::$named[$name])) throw new RouterException('No route available!', 404);
        return self::$named[$name];
    }

    public static function match(?string $uri = null)
    {
        if (!isset(self::$routes[$_SERVER['REQUEST_METHOD']])) throw new RouterException('Method Not Allowed!', 405);
        if (is_null($uri)) $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');

        foreach (self::$routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match()) return $route->call();
        }

        throw new RouterException('No route available!', 404);
    }
}