<?php

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = ''): string
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('save_old_input')) {
    function save_old_input(): void
    {
        $_SESSION['_old'] = $_POST;
    }
}

if (!function_exists('clear_old_input')) {
    function clear_old_input(): void
    {
        unset($_SESSION['_old']);
    }
}


if (!function_exists('vd')) {
    function vd($input=null): void
    {
        var_dump($input);
        die();
    }
}
