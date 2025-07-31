<?php
namespace App\Core;

class Router
{
    protected array $routes;

    public function __construct()
    {
        require_once BASE_PATH . '/routes/web.php';
        $this->routes = Route::all();
    }


//    public function loadRoutes(): void
//    {
//        require_once BASE_PATH . '/routes/web.php';
//        $this->routes = Route::all();
//    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim(str_replace('/fw/public', '', $uri), '/');

        $route = $this->routes[$method][$uri] ?? null;
        if (!$route) {
            http_response_code(404);
            echo "404 - مسیر یافت نشد: $uri";
            return;
        }


//var_dump($route['middleware']);
//        die();
        foreach ($route['middleware'] as $mw) {
            if (is_array($mw)) {
                [$class, $args] = $mw;
                (new $class(...$args))->handle();
            } else {
                (new $mw)->handle();
            }
        }
//        foreach ($route['middleware'] as $middlewareClass) {
//            if (class_exists($middlewareClass)) {
//                $middlewareClass::check();
//            } else {
//                echo "Middleware $middlewareClass یافت نشد.";
//                return;
//            }
//        }

        $controller = new $route['controller'];
        $method = $route['method'];

        if (!method_exists($controller, $method)) {
            echo "متد $method در کنترلر یافت نشد.";
            return;
        }

        $controller->$method();

//
//        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//        $uri = str_replace('/fw/public', '', $uri);
//
//        if (!isset($this->routes[$method][$uri])) {
//            http_response_code(404);
//            echo "404 - مسیر یافت نشد: $uri";
//            return;
//        }
//
//        $route = $this->routes[$method][$uri];
//        $controller = new $route['controller'];
//        $methodName = $route['method'];
//
//        // اجرای middlewareها
//        foreach ($route['middleware'] as $middleware) {
//            (new $middleware)->handle();
//        }

    }



    protected array $middlewareStack = [];

    public function get(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, array $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function addRoute(string $method, string $uri, array $action, array $middleware = []): void
    {
        $uri = rtrim($uri, '/');
        $this->routes[$method][$uri] = [
            'controller' => $action[0],
            'method' => $action[1],
            'middleware' => array_merge($this->middlewareStack, $middleware)
        ];
    }

    public function middleware(array $middleware): static
    {
        $this->middlewareStack = $middleware;
        return $this;
    }

    public function group(callable $callback): void
    {
        $callback($this);
        $this->middlewareStack = [];
    }

}
