<?php
namespace App\Core;

class Request
{
    public static function get($key = null)
    {
        if ($key) {
            return isset($_GET[$key]) ? $_GET[$key] : null;
        }
        return $_GET;
    }

    public static function post($key = null)
    {
        if ($key) {
            return isset($_POST[$key]) ? $_POST[$key] : null;
        }
        return $_POST;
    }

    public static function file($key = null)
    {
        if ($key) {
            return isset($_FILES[$key]) ? $_FILES[$key] : null;
        }
        return $_FILES;
    }

    public static function segment($index)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $segments = explode('/', trim($uri, '/'));
        return isset($segments[$index]) ? $segments[$index] : null;
    }

    public static function all()
    {
        return array_merge($_GET, $_POST, $_FILES);
    }

    public static function header($key)
    {
        return isset($_SERVER['HTTP_' . strtoupper($key)]) ? $_SERVER['HTTP_' . strtoupper($key)] : null;
    }

    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
