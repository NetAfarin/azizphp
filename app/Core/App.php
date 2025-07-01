<?php
namespace App\Core;

class App
{
    protected Router $router;

    public function __construct()
    {
        $this->initConfig();
        $this->router = new Router();
    }

    public function run()
    {
        $this->router->dispatch();
    }

    protected function initConfig()
    {
        require_once __DIR__ . '/../../config/app.php';
        require_once __DIR__ . '/../../config/database.php';
    }
}