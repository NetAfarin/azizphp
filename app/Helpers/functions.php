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
    function redirect(string $url, bool $superAdmin = false): void
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}

if (!function_exists('old')) {
    function old(string $key, string $default = '')
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
                return (array)$item;
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
if (!function_exists('getUrl')) {
    function getUrl(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (str_ends_with($scriptName, '/public')) {
            $scriptName = substr($scriptName, 0, -7);
        }
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }

        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }
        return $uri;
    }
}
function json_response($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function weekDay()
{
    return [
        "1" => "شنبه",
        "2" => "یک شنبه",
        "3" => "دو شنبه",
        "4" => "سه شنبه",
        "5" => "چهارشنبه",
        "6" => "پنج شنبه",
        "7" => "جمعه",
    ];
}

