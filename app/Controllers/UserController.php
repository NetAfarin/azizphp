<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\User;
use App\Middlewares\Role;
use App\Middlewares\Auth;


class UserController extends Controller
{
    public function show($id): void
    {

        Role::allow(['admin', 'operator']);

        $user = User::find((int)$id);

        if (!$user) {
            echo __('user_not_found');
            return;
        }

        $this->view('user/show', ['user' => $user, 'title' => __('user_profile')]);
    }

    public function register(): void
    {
        $errors = [];
        $success = false;
//        vd($_SERVER['REQUEST_METHOD']);
        if (isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "/home/index");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_POST['_csrf']) || $_POST['_csrf'] !== ($_SESSION['_csrf_token'] ?? '')) {
                http_response_code(403);
                echo "درخواست نامعتبر (CSRF)";
                exit;
            }

            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $phone = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';


            if (isset($_SESSION['validation_errors']) && is_array($_SESSION['validation_errors'])) {
                $errors = $_SESSION['validation_errors'];
                unset($_SESSION['validation_errors']);
            }

            if (User::where('phone_number', $phone)) {
                $errors[] = __('phone_taken');
            }

            if (empty($errors)) {
                $user = new \App\Models\User([
                    'first_name'        => $first_name,
                    'last_name'         => $last_name,
                    'phone_number'      => $phone,
                    'password'          => password_hash($password, PASSWORD_DEFAULT),
                    'user_type'         => 2,
                    'birth_date'        => date('2000-01-01'),
                    'register_datetime' => date('Y-m-d H:i:s'),
                    'is_active'         => 1,
                    'deleted'           => 0
                ]);

                if ($user->save()) {
                    clear_old_input();
                    $success = true;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        }
        $this->view('user/register', ['title' => 'ثبت نام کاربر', 'errors' => $errors, 'success' => $success]);
    }

    public function login(): void
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

            if (!isset($_POST['_csrf']) || $_POST['_csrf'] !== ($_SESSION['_csrf_token'] ?? '')) {
                http_response_code(403);
                echo "درخواست نامعتبر (CSRF)";
                exit;
            }

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
                    clear_old_input();
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


    public function showProfile($id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            echo __('user_not_found');
            return;
        }

        $this->view('user/show', ['user' => $user, 'title' => __('user_profile')]);
    }


    public function edit()
    {
        Auth::handle();
        $user = User::find($_SESSION['user_id']);

        $this->view('user/edit', [
            'title' => __('edit_profile'),
            'user' => $user
        ]);
    }


    public function update()
    {
        Role::allow(['admin', 'operator']);

        $errors = [];
        $user = User::find($_SESSION['user_id']);

        if (!isset($_POST['_csrf']) || $_POST['_csrf'] !== ($_SESSION['_csrf_token'] ?? '')) {
            http_response_code(403);
            echo "درخواست نامعتبر (CSRF)";
            exit;
        }

//        $validator = new Validator($_POST, [
//            'first_name'    => 'required|min:2',
//            'phone_number'  => 'required|phone',
//            'password'      => 'required|min:6'
//        ]);
//
//        if ($validator->fails()) {
//            $errors = $validator->errors();
//        }

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
                    clear_old_input();
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
