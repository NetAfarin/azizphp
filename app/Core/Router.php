<?php
namespace App\Core;

class Router
{
    protected array $routes;

    public function __construct()
    {
        require_once BASE_PATH . '/routes/web.php';
        require_once BASE_PATH . '/routes/api.php';
        $this->routes = Route::all();
    }

//    public function dispatch(): void
//    {
//        $method = $_SERVER['REQUEST_METHOD'];
//        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//        $uri = rtrim(str_replace('/fw/public', '', $uri), '/');
//
//        $route = $this->routes[$method][$uri] ?? null;
//        $routes = $this->routes[$method] ?? [];
//
//        foreach ($routes as $routePattern => $handler) {
//            $regex = preg_replace('#\{[^}]+\}#', '([^/]+)', $routePattern);
//            $regex = '#^' . $regex . '$#';
//
//            if (preg_match($regex, $uri, $matches)) {
//                array_shift($matches);
//
//                $controllerClass = $handler['controller'];
//                $controllerMethod = $handler['method'];
//                $middlewareList = $handler['middleware'] ?? [];
//
//                $request = $_REQUEST;
//
//                $finalHandler = function ($req) use ($controllerClass, $controllerMethod, $matches) {
//                    $controller = new $controllerClass();
//                    if (!method_exists($controller, $controllerMethod)) {
//                        http_response_code(500);
//                        echo "متد {$controllerMethod} در کنترلر {$controllerClass} یافت نشد.";
//                        return;
//                    }
//                    return call_user_func_array([$controller, $controllerMethod], $matches);
//                };
//
//                $middlewareChain = array_reverse($middlewareList);
//
//                foreach ($middlewareChain as $mw) {
//                    $finalHandler = function ($req) use ($mw, $finalHandler) {
//                        return (new $mw())->handle($req, $finalHandler);
//                    };
//                }
//
//                return $finalHandler($request);
//            }
//        }
//        http_response_code(404);
//        echo "404 - مسیر یافت نشد: $uri";
//        vd($this->routes[$method][$uri]);
//
//        if (!$route) {
//            http_response_code(404);
//            echo "404 - مسیر یافت نشد: $uri";
//            return;
//        }
//
//        foreach ($route['middleware'] as $mw) {
//            if (is_array($mw)) {
//                [$class, $args] = $mw;
//                (new $class(...$args))->handle();
//            } else {
//                (new $mw)->handle();
//            }
//        }
//
//        $controller = new $route['controller'];
//        $method = $route['method'];
//
//        if (!method_exists($controller, $method)) {
//            echo "متد $method در کنترلر یافت نشد.";
//            return;
//        }
//
//        $controller->$method();
//    }

//    public function dispatch(): void
//    {
//        $method = $_SERVER['REQUEST_METHOD'];
//        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
//        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//        if (strpos($uri, $scriptName) === 0) {
//            $uri = substr($uri, strlen($scriptName));
//        }
//
//        $uri = rtrim($uri, '/') ?: '/';
////        $uri = rtrim(str_replace('/fw', '', $uri), '/');
//
//        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/i', $uri)) {
//            return;
//        }
//
//        $routes = $this->routes[$method] ?? [];
//
//        foreach ($routes as $routePattern => $handler) {
//            // تبدیل مسیر دارای {id} به regex
//            $regex = preg_replace('#\{[^}]+\}#', '([^/]+)', $routePattern);
//            $regex = '#^' . $regex . '$#';
//
//            if (preg_match($regex, $uri, $matches)) {
//                array_shift($matches); // حذف match کامل
//
//                $controllerClass = $handler['controller'];
//                $controllerMethod = $handler['method'];
//                $middlewareList = $handler['middleware'] ?? [];
//
//                $request = $_REQUEST;
//
//                $finalHandler = function ($req) use ($controllerClass, $controllerMethod, $matches) {
//                    $controller = new $controllerClass();
//                    return call_user_func_array([$controller, $controllerMethod], $matches);
//                };
//
//                foreach (array_reverse($middlewareList) as $mw) {
//                    $next = $finalHandler;
//                    $finalHandler = function ($req) use ($mw, $next) {
//                        return (new $mw())->handle($req, $next);
//                    };
//                }
//
//                 $finalHandler($request);
//                return;
//            }
//        }
//
//        http_response_code(404);
//        include __DIR__ . '/../Views/errors/404.php';
//        exit;
////        echo "404 - مسیر یافت نشد: {$uri}";
//    }
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // URI کامل
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // مسیر اجرای index.php (مثلا /fw/public یا /fw)
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($scriptName, -7) === '/public') {
            $scriptName = substr($scriptName, 0, -7);
        }
        // حذف base path از URI
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }

        // حذف اسلش آخر
        $uri = rtrim($uri, '/');

        // اگر خالی شد → root
        if ($uri === '') {
            $uri = '/';
        }

        // فایل‌های استاتیک رو رد کن
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/i', $uri)) {
            return;
        }

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $routePattern => $handler) {
            // {id} رو به regex تبدیل کن
            $regex = preg_replace('#\{[^}]+\}#', '([^/]+)', $routePattern);
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);

                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                    return;
                }
                if (is_array($handler)) {
                    if (isset($handler['controller'], $handler['method'])) {
                        // ساختار associative
                        $controller = $handler['controller'];
                        $action = $handler['method'];
                        $controllerInstance = new $controller();
                        call_user_func_array([$controllerInstance, $action], $matches);
                        return;
                    } elseif (count($handler) === 2) {
                        // ساختار indexed (مثل [Controller::class, 'method'])
                        [$controller, $action] = $handler;
                        $controllerInstance = new $controller();
                        call_user_func_array([$controllerInstance, $action], $matches);
                        return;
                    }
                }
//                if (is_array($handler)) {
//                    [$controller, $action] = $handler;
//                    $controllerInstance = new $controller();
//                    call_user_func_array([$controllerInstance, $action], $matches);
//                    return;
//                }
            }
        }

        // اگه هیچ روتی match نشد
        http_response_code(404);
        include __DIR__ . '/../Views/errors/404.php';
        exit;
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
