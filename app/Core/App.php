<?php
namespace App\Core;

use App\Middleware\RateLimiterMiddleware;

class App
{
    protected Router $router;
    public function __construct()
    {
        $this->initConstants();
        $this->initConfig();
        $this->router = new Router();
    }
    public function run():void
    {
        $this->router->dispatch();
    }
    protected function initConstants(): void
    {
        define('BASE_PATH', dirname(__DIR__, 2));
    }
    protected function initConfig():void
    {
        require_once BASE_PATH . '/config/app.php';
        require_once BASE_PATH . '/config/database.php';
    }
}