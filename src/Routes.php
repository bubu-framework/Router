<?php
namespace Bubu\Router;

class Routes
{

    public string $path;
    private $callable;
    private array $matches;
    private array $params;

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function check(string $url): bool
    {
        $path = preg_replace_callback('#:([\w]+)#i', [$this, 'paramMatch'], $this->path);

        if (!preg_match("#^$path$#i", $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;

        return true;
    }

    public function with($param, $regex)
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    public function paramMatch($match)
    {
        if (isset($this->params[$match[1]])) return '(' . $this->params[$match[1]] . ')';
        return '([^/]+)';
    }

    public function call()
    {
        if (is_string($this->callable)) {
            $el = explode('#', $this->callable);
            $controller = ltrim(Router::getControllerNamespace(), "\\") . "\\" . $el[0] . Router::getControllerSuffix();
            $controller = new $controller();
            return call_user_func_array([$controller, $el[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}