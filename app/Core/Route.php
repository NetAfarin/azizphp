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
        self::$routes['GET'][$uri] = [...$action, self::$currentMiddleware];
    }

    public static function post(string $uri, array $action): void
    {
        self::$routes['POST'][$uri] = [...$action, self::$currentMiddleware];
    }

    public static function middleware(array $middleware): self
    {
        self::$currentMiddleware = $middleware;
        return new self;
    }

    public function group(callable $callback): void
    {
        $callback();
        self::$currentMiddleware = []; // reset after group
    }

    public static function all(): array
    {
        return self::$routes;
    }
}
