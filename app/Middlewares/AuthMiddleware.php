<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/user/login");
            exit;
        }

        return $next($request);
    }
}
