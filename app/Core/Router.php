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

    public function dispatch(): void
    {
        $method = Request::method();
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (str_ends_with($scriptName, '/public')) {
            $scriptName = substr($scriptName, 0, -7);
        }
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }

        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|woff|woff2|ttf|eot)$/i', $uri)) {
            return;
        }

        $routes = $this->routes[$method] ?? [];
        foreach ($routes as $routePattern => $handler) {
            $regex = preg_replace('#\{[^}]+\}#', '([^/]+)', $routePattern);
            $regex = '#^' . $regex . '$#';
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                $middlewares = $handler['middleware'] ?? [];
                $controller  = $handler['controller'];
                $action      = $handler['method'];

                $controllerInstance = new $controller();
                $request = new Request();
                //TODO check kon  injaro ke request bayad chijoori bashe
                $next = function(Request $request) use ($controllerInstance, $action, $matches) {
                    return call_user_func_array([$controllerInstance, $action], $matches);
                };

                foreach (array_reverse($middlewares) as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    $currentNext = $next;
                    $next = function(Request $request) use ($middleware, $currentNext) {
                        return $middleware->handle($request, $currentNext);
                    };
                }
                $next($request);
                return;
            }
        }
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
