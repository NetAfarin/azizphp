<?php

namespace App\Middlewares;

use App\Core\Validator;

class RegisterValidationMiddleware
{
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST, [
                'first_name'    => 'required|min:2',
                'phone_number'  => 'required|phone',
                'password'      => 'required|min:6'
            ]);

            if ($validator->fails()) {
                $_SESSION['validation_errors'] = $validator->errors();
                save_old_input();
            }
        }
    }
}
