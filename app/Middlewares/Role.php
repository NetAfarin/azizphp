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
    public static function allow(array $allowedRoles): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/user/login");
            exit;
        }
//        die($_SESSION['user_role']);
        $userRole = $_SESSION['user_role'] ?? '';

        if (!in_array($userRole, $allowedRoles)) {
            $_SESSION['flash_error'] = 'شما اجازه دسترسی به این بخش را ندارید.';
            header("Location: " . BASE_URL . "/user/login");
            exit;
        }
    }
}
