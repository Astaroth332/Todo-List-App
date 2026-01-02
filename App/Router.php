<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes;

    public function register(string $requestMethod, string $route, callable | array $action): static
    {
        $this->routes[$requestMethod][$route] = $action;
        return $this;
    }

    public function get(string $route, callable | array $action)
    {
        $this->register('GET', $route, $action);
        return $this;
    }

    public function post(string $route, callable | array $action)
    {
        $this->register('POST', $route, $action);
        return $this;
    }

    public function resolve(string $requestMethod, string $requestUri)
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $segment = explode('index.php', $path);
        $route = $segment[1];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if(is_callable($action))
        {
            return call_user_func($action);
        }

        if(is_array($action))
        {
            [$class, $method] = $action;

            if(class_exists($class))
            {
                $class = new $class();

                if(method_exists($class, $method))
                {
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}