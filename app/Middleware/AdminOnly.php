<?php
namespace App\Middleware;

class AdminOnly
{
    public static function check(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['is_admin'] ?? 0) != 1) {
            // اگر admin نیست، redirect به خانه یا 403
            header("Location: " . BASE_URL . "/forbidden");
            exit;
        }
    }
}