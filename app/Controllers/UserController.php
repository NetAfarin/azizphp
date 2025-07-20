<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            echo "کاربر پیدا نشد.";
            return;
        }

        $this->view('user/show', [
            'user' => $user,
        ]);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $success = false;
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $phone = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';

            // اعتبارسنجی ساده
            if (strlen($first_name) < 2) $errors[] = "نام خیلی کوتاه است.";
            if (strlen($phone) !== 11) $errors[] = "شماره موبایل باید ۱۱ رقم باشد.";
            if (strlen($password) < 6) $errors[] = "رمز عبور باید حداقل ۶ کاراکتر باشد.";

            if (User::where('phone_number', $phone)) {
                $errors[] = "این شماره قبلاً ثبت شده است.";
            }

            if (empty($errors)) {
                $user = new \App\Models\User([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone_number' => $phone,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'user_type' => 2, // مشتری
                    'birth_date' => date('2000-01-01'),
                    'register_datetime' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'deleted' => 0
                ]);

                if ($user->save()) {
                    $success = true;
                } else {
                    $errors[] = "ذخیره کاربر با خطا مواجه شد.";
                }
            }

            return $this->view('user/register', compact('errors', 'success'));
        }

        // نمایش فرم خام (GET)
        $this->view('user/register');
    }

    public function login()
    {
        session_start(); // شروع سشن

        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';

            if (strlen($phone) !== 11) {
                $errors[] = "شماره موبایل باید ۱۱ رقم باشد.";
            }

            if (empty($password)) {
                $errors[] = "رمز عبور نمی‌تواند خالی باشد.";
            }

            if (empty($errors)) {
                $user = \App\Models\User::where('phone_number', $phone)[0] ?? null;

                if ($user && password_verify($password, $user->password)) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->first_name;
                    $success = true;
                } else {
                    $errors[] = "اطلاعات ورود اشتباه است.";
                }
            }
        }

        $this->view('user/login', compact('errors', 'success'));
    }

}
