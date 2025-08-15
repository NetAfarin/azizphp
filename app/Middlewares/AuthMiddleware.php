<?php
namespace App\Middlewares;

use App\Core\Loggable;

class AuthMiddleware
{
    use Loggable;

    public function handle($request, $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->warning('Unauthorized access attempt', [
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                'url' => $_SERVER['REQUEST_URI'] ?? null
            ]);
            header("Location: " . BASE_URL . "/user/login");
            exit;
        }

        $this->info('Authenticated request', [
            'user_id' => $_SESSION['user_id'],
            'url' => $_SERVER['REQUEST_URI'] ?? null
        ]);

        return $next($request);
    }
}

