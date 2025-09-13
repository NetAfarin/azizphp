<?php
namespace App\Middlewares;

use App\Core\Request;

class GuestMiddleware
{
    public function handle(Request $request, $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            redirect("/home/index");
            exit;
        }

        return $next($request);
    }
}

