<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Duration;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserType;

class AdminController extends Controller
{
    public function panel()
    {
        $this->view('admin/panel', ['title' => __('admin_panel')]);
    }

    public function usersList()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = trim($_GET['search'] ?? '');
        if (!in_array($perPage, $allowedPerPage, true)) {
            $redirectUrl = '?page=1&per_page=10';
            if ($search !== '') {
                $redirectUrl .= '&search=' . urlencode($search);
            }
            header("Location: " . $redirectUrl);
            exit;
        }
        if (isset($_GET['search']) && empty($search)) {
            header("Location: " . "?page=$page&per_page=$perPage");
        }

        $query = User::query();

        if ($search !== '') {
            $query->whereLike('first_name', $search)
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('phone_number', 'LIKE', "%{$search}%");
        }
        $pagination = $query->paginate($page, $perPage);
        $this->view('admin/users',
            ['title' => __('users_list'),
                'users' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage,
                'search' => $search
            ]);
    }

    public function editUser($id)
    {
        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/users");
            exit;
        }

        $userTypes = UserType::all();
        $groupedServices = Service::groupedForSelect();
        $selectedServiceIds = [];
        $employeeServicesData = [];
        $durations = Duration::all();


        $this->view('admin/editUser', [
            'title' => __('edit_user'),
            'user' => $user,
            'userTypes' => $userTypes,
            'groupedServices' => $groupedServices,
            'selectedServiceIds' => $selectedServiceIds,
            'employeeServicesData' => $employeeServicesData,
            'durations' => $durations,
        ]);
    }

    public function updateUser($id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/users");
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $user_type = $_POST['user_type'] ?? $user->user_type;

            $newServices = [];
            if ($user_type == UserType::EMPLOYEE) {
                $newServices = $_POST['employee_services'] ?? [];
            }
            $servicePrices = $_POST['service_prices'] ?? [];

            $serviceDurations = $_POST['service_durations'] ?? [];

            foreach ($newServices as $sid) {
                if (!isset($servicePrices[$sid]) || !is_numeric($servicePrices[$sid])) {
                    $errors[] = sprintf(__('price_required_for_service'), $sid);
                }
                if (!isset($serviceDurations[$sid]) || !is_numeric($serviceDurations[$sid])) {
                    $errors[] = sprintf(__('duration_required_for_service'), $sid);
                }
            }


            if (strlen($first_name) < 2) {
                $errors[] = __('first_name_short');
            }

            foreach ($newServices as $sid) {
                if (!isset($servicePrices[$sid]) || $servicePrices[$sid] === '' || !is_numeric($servicePrices[$sid])) {
                    $errors[] = __('price_required_for_service') . " (ID: $sid)";
                }
            }

            if (empty($errors)) {
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->is_active = $is_active;
                $user->user_type = $user_type;

                if ($user->save()) {
                    $user->syncEmployeeServicesWithDetails($newServices, $servicePrices, $serviceDurations);

                    $_SESSION['flash_success'] = __('user_updated');
                    redirect("/admin/users");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }

            $this->view('admin/editUser', [
                'title' => __('edit_user'),
                'user' => $user,
                'errors' => $errors
            ]);
        }
    }

    public function addUser($userType): void
    {
//        $allowedForCurrentUser = [
//            UserType::ADMIN => ['employee','operator','customer'],
//            UserType::SYSTEM => ['admin','employee','operator','customer'],
//            UserType::EMPLOYEE => ['customer']
//        ];
//        if (!in_array($typeInput, $allowedForCurrentUser[$currentUserType] ?? [])) {
//            $errors[] = "شما اجازه ثبت این نوع کاربر را ندارید";
//        }
        $errors = [];
        $success = false;
        switch ($userType) {
            case 'user':
            case 'operator':
            case 'employee':
            case 'customer':
            case 'admin':
                $userTypeInstance = UserType::query()->where('en_title', '=', $userType)->first();
                if (!$userTypeInstance) {
                    $_SESSION['flash_error'] = __('invalid_user_type');
                    redirect("/admin/users");
                    exit;
                }
                break;
            default:
                $_SESSION['flash_error'] = __('invalid_user_type');
                redirect("/admin/users");
                exit();
        }
        $userTypes = UserType::all();
        $groupedServices = Service::groupedForSelect();
        $selectedServiceIds = [];
        $employeeServicesData = [];
        $durations = Duration::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone_number'] ?? '');
            $password = $_POST['password'] ?? '';
//            $password_confirmation = $_POST['password_confirmation'] ?? '';

//            $captcha = $_SESSION['captcha'] ?? null;
//            $userCaptcha = $_POST['captcha'] ?? '';
//            if (!$captcha || ((time() - $captcha['time']) > 120)) {
//                $errors[] = __('captcha_expired');
//            } elseif ($userCaptcha != $captcha['code']) {
//                $errors[] = __('captcha_invalid');
//            }

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
                save_old_input();
            }

            if (empty($errors)) {
                $user = new User([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
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
        $userTypeTitle = APP_LANG === 'fa' ? $userTypeInstance->title : $userTypeInstance->en_title;
        $this->view('admin/addUser', [
            'title' => __('register') . ' ' . __($userTypeTitle),
            'submitButton' => sprintf(__('add'), $userTypeTitle),
            'userType' => $userTypeInstance,
            'errors' => $errors,
            'success' => $success,
            'userTypes' => $userTypes,
            'submit_button' => $userTypes,
            'groupedServices' => $groupedServices,
            'selectedServiceIds' => $selectedServiceIds,
            'employeeServicesData' => $employeeServicesData,
        ]);
    }

    public function deleteUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        $user = User::find((int)$id);
        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/users");
            exit;
        }

        if ($user->id == $_SESSION['user_id']) {
            $_SESSION['flash_error'] = __('cannot_delete_self');
            redirect("/admin/users");
            exit;
        }
        if ($user->user_type == UserType::ADMIN) {
            $_SESSION['flash_error'] = __('cannot_delete_admin');
            redirect("/admin/users");
            exit;
        }

        if (property_exists($user, 'deleted')) {
            $user->deleted = 1;
            $success = $user->save();
        } else {
            $success = $user->delete();
        }

        if ($success) {
            $_SESSION['flash_success'] = __('user_deleted');
            Logger::info("User {$user->id} deleted by admin {$_SESSION['user_id']}");
        } else {
            $_SESSION['flash_error'] = __('delete_failed');
        }

        redirect("/admin/users");
        exit;
    }

    public function tickets()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        if (!in_array($perPage, $allowedPerPage, true)) {
            header("Location: ?page=1&per_page=10");
            exit;
        }

        $user = User::find((int)$_SESSION['user_id']);
        $pagination = Ticket::query()->where('salon_id', '=', $user->salon_id)->paginate($page, $perPage);

        $this->view('sa/tickets',
            ['title' => __('ticket_list'),
                'salons' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage
            ]);
    }

}
