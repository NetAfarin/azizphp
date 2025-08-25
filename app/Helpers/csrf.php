<?php

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        $token = csrf_token();
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars($token) . '">';
    }
}
if (!function_exists('check_csrf')) {

function check_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['_csrf'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
            http_response_code(403);
            echo "درخواست نامعتبر (CSRF)";
            exit;
        }

         unset($_SESSION['_csrf_token']);
    }
}
}
