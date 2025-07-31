<?php

namespace App\Core;

class Route
{
    protected static array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    protected static array $currentMiddleware = [];

    public static function get(string $uri, array $action): void
    {
        self::$routes['GET'][$uri] = [
            'controller' => $action[0],
            'method'     => $action[1],
            'middleware' => self::$currentMiddleware
        ];
    }

    public static function post(string $uri, array $action): void
    {
        self::$routes['POST'][$uri] = [
            'controller' => $action[0],
            'method'     => $action[1],
            'middleware' => self::$currentMiddleware
        ];
    }

    public static function middleware(array $middleware): self
    {
        self::$currentMiddleware = $middleware;
        return new self();
    }

    public function group(callable $callback): void
    {
        $callback(new self);
        self::$currentMiddleware = [];
    }

    public static function all(): array
    {
        return self::$routes;
    }
}
