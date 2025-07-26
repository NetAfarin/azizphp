<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Middlewares\Role;

class UserController extends Controller
{
    public function show($id)
    {

        Role::allow(['admin', 'operator']);

        $user = User::find((int)$id);

        if (!$user) {
            echo __('user_not_found');
            return;
        }

        $this->view('user/show', ['user' => $user, 'title' => __('user_profile')]);
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
        if (isset($_SESSION['user_id'])) {
            $redirect = ($_SESSION['is_admin'] ?? false) ? '/admin/panel' : '/home/index';
            header("Location: " . BASE_URL . $redirect);
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';

            if (strlen($phone) !== 11 || !ctype_digit($phone)) {
                $errors[] = __('phone_invalid');
            }

            if (empty($password)) {
                $errors[] = __('password_required');
            }

            if (empty($errors)) {
                $user = \App\Models\User::where('phone_number', $phone)[0] ?? null;
                if ($user && password_verify($password, $user->password)) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->first_name;
                    $_SESSION['is_admin'] = $user->is_admin;
                    $_SESSION['flash_success'] = __('login_success');
                    $user_type = \App\Models\UserType::find($user->user_type);
                    $_SESSION['user_role'] = $user_type->en_title ?? 'guest';
                    $redirect = $user->is_admin ? '/admin/panel' : '/home/index';
                    header("Location: " . BASE_URL . $redirect);
                    exit;
                } else {
                    $errors[] = __('login_failed');
                }
            }
        }

        $this->view('user/login', ['title' => __('login'), 'errors' => $errors]);
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
//        AdminOnly::check();
        \App\Middlewares\Role::allow(['admin', 'operator']);
        $this->view('admin/panel', ['title' => 'پنل مدیریت']);
    }

//    public function update()
//    {
//        if (!isset($_SESSION['user_id'])) {
//            header("Location: " . BASE_URL . "/user/login");
//            exit;
//        }
//
//        $errors = [];
//        $success = false;
//
//        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $first_name = $_POST['first_name'] ?? '';
//            $last_name = $_POST['last_name'] ?? '';
//            $phone = $_POST['phone_number'] ?? '';
//
//            if (strlen($first_name) < 2) $errors[] = __('first_name_short');
//            if (strlen($phone) !== 11) $errors[] = __('phone_invalid');
//
//            $user = User::find($_SESSION['user_id']);
//
//            if (empty($errors) && $user) {
//                $user->first_name = $first_name;
//                $user->last_name = $last_name;
//                $user->phone_number = $phone;
//                if ($user->save()) {
//                    $_SESSION['flash_success'] = __('profile_updated');
//                    header("Location: " . BASE_URL . "/user/edit");
//                    exit;
//                } else {
//                    $errors[] = __('save_error');
//                }
//            }
//
//            $this->view('user/edit', [
//                'title' => __('edit_profile'),
//                'user' => $user,
//                'errors' => $errors
//            ]);
//        }
//    }
//    public function update()
//    {
//        Auth::check();
//
//        $user = User::find($_SESSION['user_id']);
//
//        if (!$user) {
//            $_SESSION['flash_error'] = __('user_not_found');
//            header("Location: " . BASE_URL . "/user/login");
//            exit;
//        }
//
//        $first_name = $_POST['first_name'] ?? '';
//        $last_name = $_POST['last_name'] ?? '';
//        $errors = [];
//
//        if (strlen($first_name) < 2) $errors[] = __('first_name_short');
//        if (strlen($last_name) < 2) $errors[] = __('last_name_short');
//
//        if (empty($errors)) {
//            $user->first_name = $first_name;
//            $user->last_name = $last_name;
//
//            if ($user->save()) {
//                $_SESSION['flash_success'] = __('profile_updated');
//                header("Location: " . BASE_URL . "/user/profile");
//                exit;
//            } else {
//                $errors[] = __('save_error');
//            }
//        }
//
//        $this->view('user/profile', [
//            'title' => __('edit_profile'),
//            'user' => $user,
//            'errors' => $errors
//        ]);
//    }
//
//    public function edit()
//    {
//        if (!isset($_SESSION['user_id'])) {
//            header("Location: " . BASE_URL . "/user/login");
//            exit;
//        }
//
//        $user = User::find($_SESSION['user_id']);
//
//        $this->view('user/edit', [
//            'title' => __('edit_profile'),
//            'user' => $user
//        ]);
//    }


    public function userTypeTitle(): string
    {
        $types = [
            1 => __('admin'),
            2 => __('operator'),
            3 => __('customer'),
        ];

        return $types[$this->user_type] ?? __('unknown');
    }
// مشاهده پروفایل کاربر خاص (برای ادمین)
    public function showProfile($id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            echo __('user_not_found');
            return;
        }

        $this->view('user/show', ['user' => $user, 'title' => __('user_profile')]);
    }

// فرم ویرایش پروفایل خودم
    public function edit()
    {
        Auth::check();
        $user = User::find($_SESSION['user_id']);

        $this->view('user/edit', [
            'title' => __('edit_profile'),
            'user' => $user
        ]);
    }

// ذخیره ویرایش
    public function update()
    {
        Auth::check();

        $errors = [];
        $user = User::find($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $phone = $_POST['phone_number'] ?? '';

            if (strlen($first_name) < 2) $errors[] = __('first_name_short');
            if (strlen($phone) !== 11) $errors[] = __('phone_invalid');

            if (empty($errors) && $user) {
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->phone_number = $phone;

                if ($user->save()) {
                    $_SESSION['flash_success'] = __('profile_updated');
                    header("Location: " . BASE_URL . "/user/edit");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }
        }

        $this->view('user/edit', [
            'title' => __('edit_profile'),
            'user' => $user,
            'errors' => $errors
        ]);
    }



}
