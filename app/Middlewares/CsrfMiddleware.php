<?php
namespace App\Middlewares;

use App\Core\Request;

class CsrfMiddleware
{
    public function handle(Request $request, $next)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        $token = $_POST['_csrf'] ?? '';
        $sessionToken = $_SESSION['_csrf_token'] ?? '';

        if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
            http_response_code(403);
            echo "درخواست نامعتبر (CSRF)";
            exit;
        }
        return $next($request);
    }
}
