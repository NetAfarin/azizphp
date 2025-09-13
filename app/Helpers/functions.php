<?php

if (!function_exists('asset')) {
    function asset(string $relativePath): string
    {
        $fullPath = BASE_PATH . '\public\\' . ltrim($relativePath, '/');
        $version = file_exists($fullPath) ? filemtime($fullPath) : time();
        return BASE_URL . '/' . ltrim($relativePath, '/') . '?v=' . $version;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url,bool $superAdmin=false): void
    {
        if ($superAdmin) {
            header('Location: ' . BASE_URL ."/sa". $url);
        }else{
            header('Location: ' . BASE_URL ."/".SALON_ID. $url);
        }
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
    function vd($input = null, bool $jsonPretty = false): void
    {
        $convert = function ($item) use (&$convert) {
            if (is_array($item)) {
                return array_map($convert, $item);
            }
            if (is_object($item)) {
                if (method_exists($item, 'toArray')) {
                    return $item->toArray();
                }
                return (array) $item;
            }
            return $item;
        };

        $data = $convert($input);

        echo '<pre>';
        if ($jsonPretty) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            var_dump($data);
        }
        echo '</pre>';
        die();
    }
}

function json_response($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

