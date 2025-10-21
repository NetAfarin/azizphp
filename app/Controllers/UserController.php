<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Duration;
use App\Models\EmployeeService;
use App\Models\EmployeeTable;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Ticket;
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');
            $phone = trim($_POST['phone_number'] ?? '');
            $password = $_POST['password'] ?? '';
//            $password_confirmation = $_POST['password_confirmation'] ?? '';

            $captcha = $_SESSION['captcha'] ?? null;
            $userCaptcha = $_POST['captcha'] ?? '';
            if (!$captcha ||(( time() - $captcha['time'] )> 120)) {
                $errors[] = __('captcha_expired');
            } elseif ($userCaptcha != $captcha['code']) {
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
//                    if (!empty($user->salon_id)){
//                        $salon = Salon::find($user->salon_id);
//                        if (!empty($salon)&&!empty($salon->link)) {
//                            if (!defined('SALON_ID')) {
//                            define('SALON_ID', $salon->link);}
//                        }else{
//                            $errors[] = __('user_data_error');
//                        }
//                    }else{
//                        if (!defined('SALON_ID')) {
//                        define('SALON_ID', 'sa');}
//                    }
                        $_SESSION['user_id'] = $user->id;
                        $_SESSION['salon_id'] = $user->salon_id;
                        $_SESSION['user_name'] = $user->first_name;
                        $_SESSION['is_admin'] = $user->isAdmin();
                        $_SESSION['is_operator'] = $user->isOperator();
                        $_SESSION['is_super_admin'] = $user->isSuperAdmin();
                        $_SESSION['is_support'] = $user->isSupport();

                        $user_type = UserType::find($user->user_type);
                        $_SESSION['user_role'] = $user_type->en_title ?? 'guest';
                        clear_old_input();
                        $redirect =($user->isSuperAdmin() || $user->isSupport())?"/sa/dashboard":( ($user->isAdmin() || $user->isOperator())
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
    public function loginPage(): void
    {
        $errors=[];
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $redirect = (($_SESSION['is_admin'] || $_SESSION['is_operator']) ?? false)
                ? '/admin/panel' :((($_SESSION['is_super_admin'] || $_SESSION['is_support']) ?? false)?'/sa/dashboard': '/home/index');
            redirect( $redirect);
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrf();
            $phone = $_POST['phone_number'] ?? '';
            $_SESSION['phone_number'] = $phone;
            $validator = new Validator($_POST, [
                'phone_number' => 'required|phone|unique:users,phone_number',
            ]);
            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }

            if (strlen($phone) !== 11 || !ctype_digit($phone)) {
                $errors[] = __('phone_invalid');
            }
            if (empty($errors)) {
                $user = User::findByPhone($phone);
                if ($user) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['salon_id'] = $user->salon_id;
                    $_SESSION['user_name'] = $user->first_name;
                    $_SESSION['last_name'] = $user->last_name;
                    $_SESSION['is_admin'] = $user->isAdmin();
                    $_SESSION['is_operator'] = $user->isOperator();
                    $_SESSION['is_super_admin'] = $user->isSuperAdmin();
                    $_SESSION['is_support'] = $user->isSupport();
                    $user_type = UserType::find($user->user_type);
                    $_SESSION['user_role'] = $user_type->en_title ?? 'guest';
                    clear_old_input();

                    $redirect =($user->isSuperAdmin() || $user->isSupport())?"/admin/panel":( ($user->isAdmin() || $user->isOperator())
                        ? "/admin/panel" : "/home/index");
                    redirect( "/user/otp");
                    exit;
                } else {

                    redirect("/user/register2");
                    $errors[] = __('login_failed');
                }
            }
        }

        $this->view('user/originalView/login-page', [
            'title' => __('login'),
            'errors' =>$errors,
        ]);
    }
    public function registerPage()
    {
        $errors = [];
        $success = false;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $redirect = (($_SESSION['is_admin'] || $_SESSION['is_operator']) ?? false)
                ? '/admin/panel' :((($_SESSION['is_super_admin'] || $_SESSION['is_support']) ?? false)?'/sa/dashboard': '/home/index');
            redirect( $redirect);
            exit;
        }
        if(isset($_SESSION['phone_number'])){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $first_name = trim($_POST['first_name'] ?? '');
                $last_name = trim($_POST['last_name'] ?? '');
                $password = $_POST['password'] ?? '';
                $validator = new Validator($_POST, [
                    'first_name' => 'required|min:2',
                    'last_name' => 'required|min:2',
                    'password' => 'required|min:6|confirmed',
                ]);
                if ($validator->fails()) {
                    $errors = array_merge($errors, $validator->errors());
                    save_old_input();
                }
                if (User::query()->where('phone_number', '=', $_SESSION['phone_number'])->first()) {
                    $errors[] = __('phone_taken');
                }


                if (empty($errors)) {
                    $user = new User([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'phone_number' => $_SESSION['phone_number'],
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'user_type' => 2,
                        'register_datetime' => date('Y-m-d H:i:s'),
                        'is_active' => 1,
                        'deleted' => 0
                    ]);
                    if ($user->save()) {
                        clear_old_input();
                        $_SESSION['flash_success'] = __('register_success');
                        redirect("/user/otp");
                        exit;
                    } else {
                        $errors[] = __('user_save_error');
                    }
                }
            } else {
                clear_old_input();
            }
        }else{
            redirect("/user/login2");
        }

        $this->view('user/originalView/register-page', [
            'title' => __('register'),
            'errors' => $errors,
        ]);
    }
    public function add()
    {
        $errors = [];
        $user = User::find($_SESSION['user_id']);
        $salonId = $_SESSION['salon_id'] ?? 0;
        $salon = Salon::find($salonId);
        $days =APP_LANG=="fa"? [ 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه','شنبه'] : [ 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI','SAT'];
        $days=rotateArray($days,$salon->start_day_of_week);
        $durations = Duration::all();
        $service = Service::query()->where('parent_id', '<>', 0)->where("deleted", "=", 0)->get();
        $userRole = UserType::all();
        $lang = $_SESSION['lang'] ?? 'fa';
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $firstName = trim($_POST['firstname']);
           $lastname = trim($_POST['lastname']);
           $nationalCode = trim($_POST['nationalCode']);
           $phoneNumber = trim($_POST['phoneNumber']);
           $postal_address = trim($_POST['address']);
           $role = trim($_POST['role']);
           $birth_date = trim($_POST['birth_date']);
            $serviceChosen     = $_POST['service'] ?? [];
            $service_prices    = $_POST['service_prices'] ?? [];
            $service_durations = $_POST['service_durations'] ?? [];
            $employeeHolidays = $_POST['holiday'] ?? [];
            $startTime = $_POST['startTime']?? [];
            $endTime = $_POST['endTime']?? [];
            $followSalon = isset($_POST['followSalon']) && $_POST['followSalon'] === 'on' ? 1 : 0;
            if (User::query()->where('phone_number', '=', $phoneNumber)->get()) {
                $errors[] = __('phone_taken');
                save_old_input();
            }
            if (empty($errors)) {

                $user = new User([
                'first_name' => $firstName,
                'last_name' => $lastname,
                'phone_number' => $phoneNumber,
                'national_code' => $nationalCode,
                'birth_date' => $birth_date,
                'password' => "",
                'postal_address' => $postal_address,
                'register_datetime' => date('Y-m-d H:i:s'),
                'user_type' => $role,
                'follow_shift_from_salon' => $followSalon,
                'deleted' => 0,
                'is_active' => 1
            ]);
            if ($user->save()) {
                $userId = $user->id;
                if($role == "1"){
                    foreach ($serviceChosen as $index => $serviceId) {
                        $price = $service_prices[$index] ?? null;
                        $duration = $service_durations[$index] ?? null;
                        $employeeService = new EmployeeService([
                            'service_id' => $serviceId,
                            'user_id' => $userId,
                            'price' => $price,
                            'update_time' => date('Y-m-d H:i:s'),
                            'estimated_duration' => $duration,
                            'deleted' => 0,
                            'is_active' => 1
                        ]);
                        $employeeService->save();
                    }
                }
                if($role == "1" && $followSalon == 0){
                    foreach ($days as $index => $day) {
                        $off = $employeeHolidays[$index] ?? 0;
                        $startTimeWork = $startTime[$index] ?? null;
                        $endTimeWork = $endTime[$index] ?? null;

                        $employeeTable = new EmployeeTable([
                            'user_id' => $userId,
                            'start_day_of_week' => $index,
                            'off_day' => $off,
                            'start_time' => $startTimeWork,
                            'end_time' => $endTimeWork,
                        ]);
                         $employeeTable->save();
                    }
                }
                clear_old_input();
                $_SESSION['flash_success'] = __('register_success');
                redirect("/user/add");
                exit;
            } else {
                $errors[] = __('user_save_error');
            }
                }

        }

        $this->view('user/originalView/add-user', [
            'title' => __('add_user'),
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
            'lang' => $lang,
            'services' => $service,
            'days' => $days,
            'userRole' => $userRole,
            'user' => $user,
            'durations' => $durations,
        ]);
    }
    public function dashboard()
    {
        $this->view('user/originalView/userDashboard', [
            'title' => __('dashboard'),
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
        ]);
    }
    public function manageUsers()
    {
        $service = Service::query()->where('parent_id', '<>', 0)->where("deleted", "=", 0)->get();
        $userRole = UserType::query()
            ->select([
                'user_type_table.title',
//                'COUNT(ut.id) AS user_count',
            ])
            ->join('user_table as ut', 'ut.user_type', '=', 'user_type_table.id')
            ->groupBy('user_type_table.title')
            ->get();
//        vd($userRole)

        ;
        $lang = $_SESSION['lang'] ?? 'fa';
        if($_SERVER['REQUEST_METHOD'] === 'POST'){}
        $this->view('user/originalView/manageUsers', [
            'title' => __('manage_users'),
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
//            'roles' => $userRole,
        ]);
    }
    public function otpPage()
    {
        $errors = [];
        $success = false;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_SESSION['phone_number'])) {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $password = $_POST['password'] ?? '';
                $user = User::findByPhone($_SESSION['phone_number']);
                $validator = new Validator($_POST, [
                    'password' => 'required|min:6',
                ]);

                if ($validator->fails()) {
                    $errors = array_merge($errors, $validator->errors());
                    save_old_input();
                }
                $user = User::findByPhone($_SESSION['phone_number']);
                if (!$user) {
                    $errors[] = "کاربر یافت نشد.";
                }
                if (password_verify($password, $user->password)) {
                    redirect("/");
                } else {
                    $errors[] = "رمز عبور اشتباه است!";

                }
            } else {
                clear_old_input();
            }
        }
        $this->view('user/originalView/otp', [
            'title' => __('verification'),
            'errors' => $errors,
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
            'user' => $user,

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
    public function tickets()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        if (!in_array($perPage, $allowedPerPage, true)) {
            header("Location: ?page=1&per_page=10");
            exit;
        }
        $pagination = Ticket::query()->where('user_id','=',$user->id)->paginate($page, $perPage);

        $this->view('user/tickets',
            ['title' => __('ticket_list'),
                'salons' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage
            ]);
    }
}

