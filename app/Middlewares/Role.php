<?php

namespace App\Middlewares;

class Role
{
    public function __construct(protected array $allowedRoles = ['admin']) {}

    public function handle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/user/login");
            exit;
        }
        $role = $_SESSION['user_role'] ?? '';
        if (!in_array($role, $this->allowedRoles)) {
            header('Location: ' . BASE_URL . '/forbidden');
            exit;
        }
    }
}
