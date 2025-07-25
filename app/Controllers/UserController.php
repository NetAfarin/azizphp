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

        $this->view('user/show', ['user' => $user,'title' => 'پروفایل کاربر',]);
    }

    public function register()
    {
        $errors = [];
        $success = false;

        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/home/index");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $phone = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';

            // اعتبارسنجی ساده
            if (strlen($first_name) < 2) $errors[] = "نام خیلی کوتاه است.";
            if (strlen($phone) !== 11) $errors[] = "شماره موبایل باید ۱۱ رقم باشد.";
            if (strlen($password) < 6) $errors[] = "رمز عبور باید حداقل ۶ کاراکتر باشد.";

            if (User::where('phone_number', $phone)) {
//                $errors[] = "این شماره قبلاً ثبت شده است.";
                $errors[] =  __('phone_taken');
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
                    $errors[] = __('user_save_error');
                }
            }
        }
        $this->view('user/register', ['title' => 'ثبت نام کاربر', 'errors' => $errors, 'success' => $success]);
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';

            if (strlen($phone) !== 11 || !ctype_digit($phone)) {
                $errors[] = "شماره موبایل باید ۱۱ رقم عددی باشد.";
            }

            if (empty($password)) {
                $errors[] = "رمز عبور نمی‌تواند خالی باشد.";
            }

            if (empty($errors)) {
                $user = \App\Models\User::where('phone_number', $phone)[0] ?? null;
                if ($user && password_verify($password, $user->password)) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->first_name;
                    $_SESSION['is_admin'] = $user->is_admin;
                    $_SESSION['flash_success'] = __('login_success');

                    header("Location: " . BASE_URL . "/home/index");
                    exit;
                } else {
                    $errors[] = "اطلاعات ورود اشتباه است.";
                }
            }
        }

        $this->view('user/login', [
            'title' => __('login'),
            'errors' => $errors
        ]);
    }


    public function logout()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $lang = $_SESSION['lang'] ?? 'fa';

        $_SESSION['flash_success'] = __('logout_success');

        session_unset();
        session_destroy();

        header("Location: " . BASE_URL . "/user/login?lang={$lang}");
        exit;
    }
    public function panel()
    {
        AdminOnly::check();
        $this->view('admin/panel', ['title' => 'پنل مدیریت']);
    }

}
