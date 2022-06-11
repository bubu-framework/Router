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

    private static string $controllerNamespace = '';
    private static string $controllerSuffix    = '';

    private static array $named = [];

    private static function register(string $route, mixed $callable, string $method, ?string $name): Routes
    {
        $newRoute = new Routes($route, $callable);
        self::$routes[$method][$route] = $newRoute;
        if (!is_null($name)) self::$named[] = $route;
        elseif (is_string($callable)) self::$named[] = $callable;
        return $newRoute;
    }

    public static function get(string $route, mixed $callable, ?string $name = null): Routes
    {
        return self::register($route, $callable, 'GET', $name);
    }

    public static function post(string $route, mixed $callable, ?string $name = null): Routes
    {
        return self::register($route, $callable, 'POST', $name);
    }

    public static function put(string $route, mixed $callable, ?string $name = null): Routes
    {
        return self::register($route, $callable, 'PUT', $name);
    }

    public static function patch(string $route, mixed $callable, ?string $name = null): Routes
    {
        return self::register($route, $callable, 'PATCH', $name);
    }

    public static function delete(string $route, mixed $callable, ?string $name = null): Routes
    {
        return self::register($route, $callable, 'DELETE', $name);
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

    public static function check(?string $uri = null)
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], array_keys(self::$routes))) throw new RouterException('Method Not Allowed!', 405);
        if (is_null($uri)) $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');
        foreach (self::$routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->check($uri)) return $route->call();
        }

        throw new RouterException('No route available!', 404);
    }
}