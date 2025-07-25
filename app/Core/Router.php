<?php
namespace App\Core;

class Router
{
    protected array $routes;

    public function __construct()
    {
        require BASE_PATH . '/routes/web.php';
        $this->routes = Route::all();
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/fw/public', '', $uri);

        if (isset($this->routes[$method][$uri])) {
            [$controller, $action, $middlewares] = $this->routes[$method][$uri];

            // اجرای middlewareها
            foreach ($middlewares as $mw) {
                $mwClass = "App\\Middleware\\" . ucfirst($mw);
                if (class_exists($mwClass) && method_exists($mwClass, 'check')) {
                    $mwClass::check();
                }
            }

            $instance = new $controller;
            $instance->$action();
        } else {
            http_response_code(404);
            echo "404 - مسیر یافت نشد: $uri";
        }
    }

}
