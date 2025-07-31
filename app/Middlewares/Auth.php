<?php

namespace App\Middlewares;

class Auth
{
   public static function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/user/login");
            exit;
        }
    }
}
