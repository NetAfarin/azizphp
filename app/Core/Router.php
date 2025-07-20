<?php
namespace App\Core;

class Router
{
    public function dispatch()
    {
        $url = $_GET['url'] ?? '';
        $url = trim($url, '/');
        $segments = explode('/', $url);

        $controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
        $method = $segments[1] ?? 'index';
        $params = array_slice($segments, 2);

        $controllerFile = BASE_PATH . '/app/Controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerClass = 'App\\Controllers\\' . $controllerName;
            $controller = new $controllerClass;

            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
            } else {
                echo "Method '$method' not found";
            }
        } else {
            echo "Controller '$controllerName' not found";
        }
    }
}
