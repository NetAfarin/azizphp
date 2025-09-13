<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\User;
use App\Middlewares\RoleMiddleware;
use App\Models\UserType;

class UserController extends Controller
{
    public function show($id): void
    {
        $user = User::find((int)$id);

        if (!$user) {
            echo __('user_not_found');
            return;
        }

        $this->view('user/show', [
            'user' => $user,
            'title' => __('user_profile')
        ]);
    }

    public function register(): void
    {
        $errors = [];
        $success = false;

        if (isset($_SESSION['user_id'])) {
            redirect("/home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone_number'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirmation = $_POST['password_confirmation'] ?? '';

            $userCaptcha = $_POST['captcha'] ?? '';
            $sessionCaptcha = $_SESSION['captcha'] ?? '';

            $captcha = $_SESSION['captcha'] ?? null;
            $userCaptcha = $_POST['captcha'] ?? '';

            if (!$captcha || time() - $captcha['time'] > 120) {
                $errors[] = __('captcha_expired');
            } elseif ($userCaptcha !== $captcha['code']) {
                $errors[] = __('captcha_invalid');
            }


            $validator = new Validator($_POST, [
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'phone_number' => 'required|phone|unique:users,phone_number',
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required|same:password',
                'birth_date' => 'required|date'
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }

            if (User::query()->where('phone_number', '=', $phone)->first()) {
                $errors[] = __('phone_taken');
            }

            if (empty($errors)) {
                $user = new User([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'salon_id' => SALON_ID,
                    'phone_number' => $phone,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'user_type' => 2,
                    'birth_date' => $_POST['birth_date'],
                    'register_datetime' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'deleted' => 0
                ]);

                if ($user->save()) {
                    clear_old_input();
                    $_SESSION['flash_success'] = __('register_success');
                    redirect("/user/login");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        } else {
            clear_old_input();
        }

        $this->view('user/register', [
            'title' => __('register'),
            'errors' => $errors,
            'success' => $success
        ]);
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $redirect = (($_SESSION['is_admin'] || $_SESSION['is_operator']) ?? false)
                ? '/admin/panel' :((($_SESSION['is_super_admin'] || $_SESSION['is_support']) ?? false)?'/sa/dashboard': '/home/index');
            redirect( $redirect);
            exit;
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $phone = $_POST['phone_number'] ?? '';
            $password = $_POST['password'] ?? '';

            if (strlen($phone) !== 11 || !ctype_digit($phone)) {
                $errors[] = __('phone_invalid');
            }
            if (empty($password)) {
                $errors[] = __('password_required');
            }

            if (empty($errors)) {
                $user = User::findByPhone($phone);
                if ($user && password_verify($password, $user->password)) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->first_name;
                    $_SESSION['is_admin'] = $user->isAdmin();
                    $_SESSION['is_operator'] = $user->isOperator();
                    $_SESSION['is_super_admin'] = $user->isSuperAdmin();
                    $_SESSION['is_support'] = $user->isSupport();

                    $user_type = UserType::find($user->user_type);
                    $_SESSION['user_role'] = $user_type->en_title ?? 'guest';
                    clear_old_input();
                    $redirect =($user->isSuperAdmin() || $user->isSupport())?"/dashboard":( ($user->isAdmin() || $user->isOperator())
                        ? "/admin/panel" : "/home/index");
                    redirect( $redirect,($user->isSuperAdmin() || $user->isSupport()));
                    exit;
                } else {
                    $errors[] = __('login_failed');
                }
            }
        }

       $this->view('user/login', [
            'title' => __('login'),
            'errors' => $errors
        ]);
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $lang = $_SESSION['lang'] ?? 'fa';
        $_SESSION['flash_success'] = __('logout_success');

        session_unset();
        session_destroy();

        redirect("/user/login?lang={$lang}");
        exit;
    }

    public function panel(): void
    {
        $this->view('admin/panel', ['title' => 'پنل مدیریت']);
    }

    public function profile(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $user = User::find($userId);

        if (!$user) {
            $this->view('errors/404', ['message' => __('user_not_found')]);
            return;
        }

        $this->view('user/show', [
            'user' => $user,
            'title' => __('user_profile')
        ]);
    }

    public function edit(): void
    {
        $user = User::find($_SESSION['user_id']);

        $this->view('user/edit', [
            'title' => __('edit_profile'),
            'user' => $user
        ]);
    }

    protected function checkCsrf(): void
    {
        if (!isset($_POST['_csrf']) || $_POST['_csrf'] !== ($_SESSION['_csrf_token'] ?? '')) {
            http_response_code(403);
            echo "درخواست نامعتبر (CSRF)";
            exit;
        }
    }

    public function update(): void
    {
        RoleMiddleware::allow(['admin', 'operator']);

        $errors = [];
        $user = User::find($_SESSION['user_id']);

        $this->checkCsrf();

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
                    redirect("/user/edit");
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

