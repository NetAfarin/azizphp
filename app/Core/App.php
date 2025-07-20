<?php
namespace App\Core;

class App
{
    protected Router $router;

    public function __construct()
    {
        $this->initConstants();
        $this->initConfig();
        $this->router = new Router();
    }

    public function run()
    {
        $this->router->dispatch();
    }
    protected function initConstants()
    {
        define('BASE_PATH', dirname(__DIR__, 2)); // مسیر ریشه پروژه
    }

    protected function initConfig()
    {
        require_once BASE_PATH . '/config/app.php';
        require_once BASE_PATH . '/config/database.php';
    }
}