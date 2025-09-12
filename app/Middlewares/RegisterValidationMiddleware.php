<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Validator;

class RegisterValidationMiddleware
{
    public function handle(Request $request, $next)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $validator = new Validator($_POST, [
                'first_name'    => 'required|min:2',
                'phone_number'  => 'required|phone',
                'password'      => 'required|min:6'
            ]);

            if ($validator->fails()) {
                $_SESSION['validation_errors'] = $validator->errors();
                save_old_input();
                // می‌تونی اینجا redirect یا exit بزاری اگر بخوای
                // مثلا:
                // header('Location: ' . $_SERVER['HTTP_REFERER']);
                // exit;
            }
        }

        return $next($request);
    }
}
