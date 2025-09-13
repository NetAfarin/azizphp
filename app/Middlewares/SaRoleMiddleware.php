<?php

namespace App\Middlewares;

use App\Core\Request;

class SaRoleMiddleware
{
    protected array $allowedRoles;

    public function __construct(array $allowedRoles = ['super_admin','support'])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(Request $request, $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            redirect("/user/login");
            exit;
        }

        $role = $_SESSION['user_role'] ?? '';
        if (!in_array($role, $this->allowedRoles)) {
            header('Location: ' . BASE_URL . '/forbidden');
            exit;
        }

        return $next($request);
    }
}
