<?php
namespace App\Middlewares;

class CsrfMiddleware
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf'] ?? '';
            $sessionToken = $_SESSION['_csrf_token'] ?? '';

            if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
                http_response_code(403);
                echo "درخواست نامعتبر (CSRF)";
                exit;
            }
        }
    }
}
