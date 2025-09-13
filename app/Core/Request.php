<?php
namespace App\Core;
namespace App\Core;

class Request
{
    private $query;
    private $post;
    private $files;
    private $server;
    public function __construct()
    {
        // بارگذاری مقادیر از سوپرگلوبال‌ها
        $this->query = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    public function get($key = null)
    {
        if ($key) {
            return isset($this->query[$key]) ? $this->query[$key] : null;
        }
        return $this->query;
    }

    public function post($key = null)
    {
        if ($key) {
            return isset($this->post[$key]) ? $this->post[$key] : null;
        }
        return $this->post;
    }

    public function file($key = null)
    {
        if ($key) {
            return isset($this->files[$key]) ? $this->files[$key] : null;
        }
        return $this->files;
    }

    public function segment($index)
    {
        $uri = $this->server['REQUEST_URI'];  // استفاده از داده‌های سوپرگلوبال $_SERVER
        $segments = explode('/', trim($uri, '/'));
        return isset($segments[$index]) ? $segments[$index] : null;
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function all()
    {
        return array_merge($_GET, $_POST, $_FILES);
    }
}
