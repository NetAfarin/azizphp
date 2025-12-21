<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\AwarenessSourceTable;
use App\Models\Booking;
use App\Models\Duration;
use App\Models\EmployeeService;
use App\Models\EmployeeTable;
use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceVisitRelation;
use App\Models\SurveysTable;
use App\Models\Ticket;
use App\Models\User;
use App\Middlewares\RoleMiddleware;
use App\Models\UserType;
use App\Models\VisitStatus;

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
                ? '/admin/panel' : ((($_SESSION['is_super_admin'] || $_SESSION['is_support']) ?? false) ? '/sa/dashboard' : '/home/index');
            redirect($redirect);
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
                    $redirect = ($user->isSuperAdmin() || $user->isSupport()) ? "/sa/dashboard" : (($user->isAdmin() || $user->isOperator())
                        ? "/admin/panel" : "/home/index");
                    redirect($redirect, ($user->isSuperAdmin() || $user->isSupport()));
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
        $errors = [];
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $redirect = (($_SESSION['is_admin'] || $_SESSION['is_operator']) ?? false)
                ? '/admin/panel' : ((($_SESSION['is_super_admin'] || $_SESSION['is_support']) ?? false) ? '/sa/dashboard' : '/home/index');
            redirect($redirect);
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
                    redirect("/user/otp");
                    exit;
                } else {

                    redirect("/user/register2");
                    $errors[] = __('login_failed');
                }
            }
        }

        $this->view('user/originalView/loginPage', [
            'title' => __('login'),
            'errors' => $errors,
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
                ? '/admin/panel' : ((($_SESSION['is_super_admin'] || $_SESSION['is_support']) ?? false) ? '/sa/dashboard' : '/home/index');
            redirect($redirect);
            exit;
        }
        if (isset($_SESSION['phone_number'])) {
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
                        'salon_id' => 1,
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
        } else {
            redirect("/user/login2");
        }

        $this->view('user/originalView/registerPage', [
            'title' => __('register'),
            'errors' => $errors,
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
    public function updateStatusType($id)
    {
        header('Content-Type: application/json');
        $getId = (int)$id;
        $getStatus = ServiceVisitRelation::find($getId);
        if (!$getStatus) {
            echo json_encode(['success' => false, 'message' => 'سرویس یافت نشد']);
            exit;
        }

        $statusType = (int)($_POST['status_id'] ?? 0);
        $employeeId = (int)($_POST['employee_id'] ?? 0);
        $dateTime = ($_POST['register_datetime'] ?? "00:00:00");
        //todo agar anjam shode bod, baz betone edit kone?
        $getStatus->visit_status = $statusType;

        if($statusType == 5){
            $survey = new SurveysTable([
                'service_visit_relation_id' => $getStatus->id,
                'salon_id' => $getStatus->salon_id ?? 1,
                'employee_id' => $employeeId,
                'register_datetime' => $dateTime,
                'submitted' => 0,
                'link' => randomString(),
                'submit_datetime' => date('Y-m-d H:i:s'),
                'survey_datetime' => date('Y-m-d H:i:s'),
                'deleted' => 0
            ]);
            $survey->save();
        }
        if ($getStatus->save()) {
            echo json_encode(['success' => true]);
            $_SESSION['flash_success'] = __('status_change_successfully');
            exit;
        }
        else {
            echo json_encode(['success' => false]);
            $_SESSION['flash_danger'] = __('status_cant_change');
            exit;
        }
    }

    public function operatorDashboard()
    {
        $allowedPerPage = [1, 10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowedPerPage) ? (int)$_GET['per_page'] : 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $search = trim($_GET['search'] ?? '');
        $getStatus = isset($_GET['status']) ? (int)$_GET['status'] : 0;
        $lang = $_SESSION['lang'] ?? 'fa';
        $column = "ut.first_name";
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
        if ($getStatus != 0) {
            $query = ServiceVisitRelation::visitsDetailsWithStatusType($getStatus);
            if (!empty($search)) {
                $query = $query->whereLike("c.first_name", $search)->orWhereLike("service_table.fa_title", $search);

            }
//            vd($query->paginate($page, $perPage));

        } else {
            $query = ServiceVisitRelation::visitsDetails();
            if (!empty($search)) {
                $query = $query->whereLike("c.first_name", $search)->orWhereLike("service_table.fa_title", $search);
            }
        }
        $pagination = $query->paginate($page, $perPage);
        $doneServiceYesterday = sizeof(ServiceVisitRelation::visitsDetailsWithStatusType(5)->where("DATE_FORMAT(vt.visit_datetime, '%Y-%m-%d')", "=", date("Y-m-d", strtotime("-1 day")))->get());
        $cancelledServiceYesterday = sizeof(ServiceVisitRelation::visitsDetailsWithStatusType(3)->where("DATE_FORMAT(vt.visit_datetime, '%Y-%m-%d')", "=", date("Y-m-d", strtotime("-1 day")))->get());
        $todayVisitsCount = ServiceVisitRelation::getVisitsNumberToday();
        $visitStatus = VisitStatus::all();
        $doneServicesCount = sizeof(ServiceVisitRelation::visitsDetailsWithStatusType(5)->where("DATE_FORMAT(vt.visit_datetime, '%Y-%m-%d')", "=", date("Y-m-d"))->get());
        $pendingServicesCount = sizeof(ServiceVisitRelation::visitsDetailsWithStatusType(2)->where("DATE_FORMAT(vt.visit_datetime, '%Y-%m-%d')", "=", date("Y-m-d"))->get());
        $customersCount = sizeof(User::all());
        $totalPages = ceil($pagination['total'] / $perPage);
        $this->view('user/originalView/operatorDashboard', [
            'title' => __('dashboard'),
            'renderPagination' => renderPagination($totalPages, $page, $perPage, $search, $lang),
            'pagination' => $pagination,
            'todayVisitCount' => sizeof($todayVisitsCount),
            'doneServiceYesterday' => $doneServiceYesterday,
            'cancelledServiceYesterday' => $cancelledServiceYesterday,
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
            'visits' => $pagination['data'],
            'search' => $search,
            'visitStatus' => $visitStatus,
            'getStatus' => $getStatus,
            'doneServicesCount' => $doneServicesCount,
            'pendingServicesCount' => $pendingServicesCount,
            'customersCount' => $customersCount,
            'per_page' => $perPage,
            'page' => $totalPages
        ]);
    }
    public function otpPage()
    {
        $errors = [];
        $success = false;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['phone_number'])) {

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
                    redirect("/admin/user/add");
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

        redirect("/user/login2?lang={$lang}");
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
        $pagination = Ticket::query()->where('user_id', '=', $user->id)->paginate($page, $perPage);

        $this->view('user/tickets',
            ['title' => __('ticket_list'),
                'salons' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage
            ]);
    }

    public function getUserData($id)
    {
        $user = User::query()
            ->select([
                'user_table.id',
                'user_table.first_name',
                'user_table.last_name',
                'user_table.phone_number',
                'utt.id as role_id',
                (APP_LANG === 'fa' ? 'utt.title' : 'utt.en_title') . ' AS user_type'
            ])
            ->join('user_type_table as utt', 'user_table.user_type', '=', 'utt.id')
            ->where('user_table.id', '=', $id)
            ->first();

        if (!$user) {
            http_response_code(403);
            echo json_encode(['error' => 'invalid user']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($user->toArray());
        exit;
    }

    public function updateService($id)
    {
        header('Content-Type: application/json');
        $errors = [];

        $service = User::find($id);
        if (!$service) {
            echo json_encode(['success' => false, 'message' => 'سرویس یافت نشد']);
            exit;
        }

        $editFaTitle = trim($_POST['fa_title'] ?? '');
        $editEnTitle = trim($_POST['en_title'] ?? '');
        $isCategory = (int)($_POST['checkBoxCategory'] ?? 0);
        $categoryModal = $_POST['category_modal'] ?? null;

        $validator = new Validator($_POST, [
            'fa_title' => 'required|min:2|max:40',
            'en_title' => 'required|min:2|max:40',
        ]);

        if ($validator->fails()) {
            $errors = array_merge($errors, $validator->errors());
            echo json_encode(['success' => false, 'message' => $errors]);
            exit;
        }
        $service->fa_title = $editFaTitle;
        $service->en_title = $editEnTitle;
        $service->service_key = "";

        if ($isCategory === 1) {
            $service->parent_id = 0;
        } else {
            $service->parent_id = !empty($categoryModal) ? $categoryModal : $service->parent_id;
        }

        if ($service->save()) {
            echo json_encode(['success' => true, 'message' => 'بروزرسانی با موفقیت انجام شد']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'خطا در ذخیره‌سازی']);
            exit;
        }
    }


    public function updateUser2($id)
    {
        header('Content-Type: application/json');
        $errors = [];

        $user = User::find($id);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'کاربر یافت نشد']);
            exit;
        }

        $editFirstName = trim($_POST['first_name'] ?? '');
        $editLastName = trim($_POST['last_name'] ?? '');
        $phoneNumber = trim($_POST['phone_number'] ?? '');
        $roles = trim($_POST['roles'] ?? '');

        $validator = new Validator($_POST, [
            'first_name' => 'required|min:2|max:40',
            'last_name' => 'required|min:2|max:40',
            'phone_number' => 'required|min:11|max:11',
        ]);

        if ($validator->fails()) {
            $errors = array_merge($errors, $validator->errors());
            echo json_encode(['success' => false, 'message' => $errors]);
            exit;
        }
        if (!empty($editFirstName)) $user->first_name = $editFirstName;
        if (!empty($editLastName)) $user->last_name = $editLastName;
        if (!empty($phoneNumber)) $user->phone_number = $phoneNumber;
        if (!empty($roles)) $user->user_type = $roles;

        if ($user->save()) {
            echo json_encode(['success' => true, 'message' => 'بروزرسانی با موفقیت انجام شد']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'خطا در ذخیره‌سازی']);
            exit;
        }
    }


    public function reserveList()
    {
        $lang = $_SESSION['lang'] ?? 'fa';
        $sortBy = $_GET['sortby'] ?? '';
        $sortOrder = $_GET['sortorder'] ?? 'asc';
        $filter = trim($_GET['filter'] ?? 'all');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = (int)($_GET['per_page'] ?? 10);
        $allowedPerPage = [10, 20, 50, 100];
        $search = trim($_GET['search'] ?? '');

        $sortServiceUrl = BASE_URL . '/user/reserve?sortby=service&sortorder=' . (($sortBy == 'service' && $sortOrder == 'asc') ? 'desc' : 'asc');
        $sortEmployeeUrl = BASE_URL . '/user/reserve?sortby=employee&sortorder=' . (($sortBy == 'employee' && $sortOrder == 'asc') ? 'desc' : 'asc');
        $sortDateUrl = BASE_URL . '/user/reserve?sortby=date&sortorder=' . (($sortBy == 'date' && $sortOrder == 'asc') ? 'desc' : 'asc');
        $fromDate = $_GET['from_date'] ?? null;
        $toDate = $_GET['to_date'] ?? null;
        $allSearchData = ServiceVisitRelation::visitsDetails();
        $allReserve2 = ServiceVisitRelation::visitsDetails();
        $allReserveVisits2 = ServiceVisitRelation::visitsDetailsWithStatusType(1);
        $allVerifyVisits2 = ServiceVisitRelation::visitsDetailsWithStatusType(2);
        $allCancelledVisits2 = ServiceVisitRelation::visitsDetailsWithStatusType(3);
        $allInProgressVisits2 = ServiceVisitRelation::visitsDetailsWithStatusType(4);
        $allDoneVisits2 = ServiceVisitRelation::visitsDetailsWithStatusType(5);
        $allNoShowVisits2 = ServiceVisitRelation::visitsDetailsWithStatusType(6);
        $allRescheduledVisits2 = ServiceVisitRelation::visitsDetailsWithStatusType(7);

        $sortByColumn = "vt.visit_datetime";
        if ($sortBy === 'service') {
            $sortByColumn = $lang == 'fa' ? 'st.fa_title' : 'st.en_title';
        } elseif ($sortBy === 'employee') {
            $sortByColumn = 'ut.first_name';
        } elseif ($sortBy === 'date') {
            $sortByColumn = 'vt.visit_datetime';
        }
        $column = $lang == "fa" ? "service_table.fa_title" : 'st.fa_title';
        $allSearchData = ServiceVisitRelation::visitsDetails();
        $allReserve = ServiceVisitRelation::visitsDetails();
        $allReserveVisits = ServiceVisitRelation::visitsDetailsWithStatusType(1);
        $allVerifyVisits = ServiceVisitRelation::visitsDetailsWithStatusType(2);
        $allCancelledVisits = ServiceVisitRelation::visitsDetailsWithStatusType(3);
        $allInProgressVisits = ServiceVisitRelation::visitsDetailsWithStatusType(4);
        $allDoneVisits = ServiceVisitRelation::visitsDetailsWithStatusType(5);
        $allNoShowVisits = ServiceVisitRelation::visitsDetailsWithStatusType(6);
        $allRescheduledVisits = ServiceVisitRelation::visitsDetailsWithStatusType(7);
        if ($search !== '') {
            $allSearchData->whereLike($column, $search);
            $allReserve->whereLike($column, $search);
            $allReserveVisits->whereLike($column, $search);
            $allCancelledVisits->whereLike($column, $search);
            $allDoneVisits->whereLike($column, $search);
            $allReserve2->whereLike($column, $search);
            $allReserveVisits2->whereLike($column, $search);
            $allCancelledVisits2->whereLike($column, $search);
            $allDoneVisits2->whereLike($column, $search);
        }

        if (!empty($fromDate) && !empty($toDate)) {
            list($jy1, $jm1, $jd1) = explode('-', $fromDate);
            list($jy2, $jm2, $jd2) = explode('-', $toDate);
            $fromDate = jalali_to_gregorian($jy1, $jm1, $jd1, '-');
            $toDate = jalali_to_gregorian($jy2, $jm2, $jd2, '-');
            $allReserve2->where("vt.visit_datetime", ">=", $fromDate)->where("vt.visit_datetime", "<=", $toDate);
            $allReserveVisits2->where("vt.visit_datetime", ">=", $fromDate)->where("vt.visit_datetime", "<=", $toDate);
            $allDoneVisits2->where("vt.visit_datetime", ">=", $fromDate)->where("vt.visit_datetime", "<=", $toDate);
            $allCancelledVisits2->where("vt.visit_datetime", ">=", $fromDate)->where("vt.visit_datetime", "<=", $toDate);
        }
        switch ($filter) {
            case 'all':
                $pagination = $allReserve2->paginate($page, $perPage);
                break;
            case 'reserved':
                $pagination = $allReserveVisits2->paginate($page, $perPage);
                break;
            case 'done':
                $pagination = $allDoneVisits2->paginate($page, $perPage);
                break;
            case 'cancelled':
                $pagination = $allCancelledVisits2->paginate($page, $perPage);
                break;
            case 'verify':
                $pagination = $allVerifyVisits2->paginate($page, $perPage);
                break;
            case 'no_show':
                $pagination = $allNoShowVisits2->paginate($page, $perPage);
                break;
            case 'rescheduled':
                $pagination = $allRescheduledVisits2->paginate($page, $perPage);
                break;
            case 'in_progress':
                $pagination = $allInProgressVisits2->paginate($page, $perPage);
        }
        $searchItems = sizeof($allSearchData->get());
        $allVisitsSize = sizeof($allReserve->get());
        $allReserveVisitsSize = sizeof($allReserveVisits->get());
        $allCancelledVisitsSize = sizeof($allCancelledVisits->get());
        $allDoneSize = sizeof($allDoneVisits->get());
        $allVerifySize = sizeof($allVerifyVisits->get());
        $allInProgressSize = sizeof($allInProgressVisits->get());
        $allNoShowSize = sizeof($allNoShowVisits->get());
        $allRescheduledSize = sizeof($allRescheduledVisits->get());
        $totalPages = ceil($pagination['total'] / $perPage);
        $this->view('user/originalView/customerReserve', [
            'title' => __('booking_list'),
            'first_name' => $_SESSION['user_name'] ?? "",
            'last_name' => $_SESSION['last_name'] ?? "",
            'search' => $search,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'searchItems' => $searchItems,
            'allowedPerPage' => $allowedPerPage,
            'perPage' => $perPage,
            'sortServiceUrl' => $sortServiceUrl,
            'sortEmployeeUrl' => $sortEmployeeUrl,
            'sortDateUrl' => $sortDateUrl,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $totalPages,
            'pagination' => $pagination,
            'allReserve' => $pagination['data'],
            'allVisits' => $allVisitsSize,
            'visitsReserved' => $allReserveVisitsSize,
            'allCancelledVisitsSize' => $allCancelledVisitsSize,
            'allDoneVisitsSize' => $allDoneSize,
            'allVerifySize' => $allVerifySize,
            'allInProgressVisits' => $allInProgressSize,
            'allNoShowSize' => $allNoShowSize,
            'allRescheduledSize' => $allRescheduledSize,
            'renderPagination' => renderPagination($totalPages, $page, $perPage, $search, $sortBy, $sortOrder, $filter, $lang),
        ]);
    }

    public function submitComment()
    {
        $doneVisits = ServiceVisitRelation::query()->select(["ut.first_name as employeeFirstName, ut.last_name as employeeLastName , vt.visit_datetime as visitDatetime , ut.last_name , st.fa_title as service"])
            ->join("user_table as ut", "ut.id", "=", "service_visit_relation_table.employee_id")
            ->join("service_table as st", "st.id", "=", "service_visit_relation_table.service_id")
            ->join("visit_table as vt", "vt.id", "=", "service_visit_relation_table.visit_id")
            ->where("service_visit_relation_table.visit_status", "=", 5)
            ->first();
        $this->view('user/originalView/submitComment', [
            'title' => __('survey'),
            'first_name' => $_SESSION['user_name'] ?? "",
            'last_name' => $_SESSION['last_name'] ?? "",
            'doneVisits' => $doneVisits,
        ]);
    }
    public function submitRank()
    {
        $lang =$_GET['lang'] ?? "fa";
        $link =$_GET['link'] ?? "";
        $errors = [];
        $survey = SurveysTable::query()->where("link" , "=" , $link)->first();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $qualityScore = $_POST['service_quality'] ?? "";
            $toolsScore = $_POST['tools_quality']?? "";
            $employeeBehavior = $_POST['employee_behavior']?? "";
            $onTimeScore = $_POST['on_time']?? "";
            $source = $_POST['source']?? "";
            $other = $_POST['other']?? "";
            $suggestions = $_POST['suggestions']?? "";
            //todo add these words to fa.php for show error fields
            $validator = new Validator($_POST, [
                'service_quality' => 'required|min:1|max:5',
                'tools_quality' => 'required|min:1|max:5',
                'employee_behavior' => 'required|min:1|max:5',
                'on_time' => 'required|min:1|max:5',
                'source' => 'required|min:1|max:5',
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }
            if(empty($errors)){
                if($survey->submitted != 1){
                    $survey->quality_score_id = $qualityScore;
                    $survey->behavior_score = $employeeBehavior;
                    $survey->onTime_score = $onTimeScore;
                    $survey->tools_score = $toolsScore;
                    $survey->feedback_text = $suggestions;
                    $survey->awareness_source_id = $source;
                    $survey->awareness_source_text = $other;
                    $survey->submitted = 1;
                    if($survey->save()){
                        $_SESSION["flash_success"] = __("your_survey_has_been_saved");
                        redirect("/");
                    }
                }else{
                    $_SESSION["flash_error"] = __("survey_already_submitted");
                }
            }
        }
        $source = AwarenessSourceTable::all();
        $this->view('user/rank',
            ['title' => __('ticket_list'),
                'source' => $source,
                'lang' => $lang,
                'errors' => $errors,

            ]);
    }
}

