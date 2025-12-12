<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Duration;
use App\Models\EmployeeService;
use App\Models\EmployeeTable;
use App\Models\Holiday;
use App\Models\PreBooking;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserType;
use Cassandra\Date;
use DateTime;

class AdminController extends Controller
{
    public function panel()
    {
        $this->view('admin/panel', ['title' => __('admin_panel')]);
    }

    public function usersList()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $sortBy = isset($_GET['sortby']) ? $_GET['sortby'] : '';
        $sortOrder = isset($_GET['sortorder']) ? $_GET['sortorder'] : '';

        $sortUrl = (BASE_URL . '/admin/users?sortby=%s&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');
        //TODO handle e per page dar link haye sort va pagination

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

        $query = User::query()->select(['user_table.*', 'ut.title AS role'])->join('user_type_table ut', 'user_table.user_type', '=', 'ut.id');

        if ($search !== '') {
            $query->whereLike('first_name', $search)
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('phone_number', 'LIKE', "%{$search}%");
        }
        if (!empty($sortBy)) {
            $query->orderBy($sortBy, $sortOrder);
        }
        $pagination = $query->paginate($page, $perPage);
//        vd($pagination);
        $this->view('admin/users',
            ['title' => __('users_list'),
                'users' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage,
                'search' => $search,
                'sortBy' => $sortBy,
                'sortOrder' => $sortOrder,
                'sortUrl' => $sortUrl,

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
        $employeeServicesData = EmployeeService::query()->join('service_table AS srv','srv.id','=','employee_service_table.service_id')->select(['employee_service_table.*', (APP_LANG === 'fa' ? 'srv.fa_title' : 'srv.en_title').' AS title'])->where('user_id', '=', $id)->get() ?? [];
        $selectedServiceIds =[];
        $durations = Duration::all();

        foreach ($employeeServicesData as $service) {
            $selectedServiceIds[] = $service->service_id;
        }

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
                    'salon_id' => 1,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'user_type' => $userTypeInstance->id,
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
        $selectedServiceIds = old('employee_services') ?? [];
        $prices = old('service_prices') ?? [];
        $employeeServicesData = [];
        if (empty($selectedServiceIds)) {
            $selectedServiceIds = [];
        }
        foreach ($selectedServiceIds as $serviceId) {
            foreach ($groupedServices as $duration_item) {
                foreach ($duration_item['children'] as $service) {
                    if ($service->id == $serviceId) {
                        $employeeServicesData [] = $service;
                    }
                }
            }
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
            'durations' => $durations,
            'prices' => $prices,
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
            redirect("/admin/user/manage");
            exit;
        }

        if ($user->id == $_SESSION['user_id']) {
            $_SESSION['flash_error'] = __('cannot_delete_self');
            redirect("/admin/user/manage");
            exit;
        }
        if ($user->user_type == UserType::ADMIN) {
            $_SESSION['flash_error'] = __('cannot_delete_admin');
            redirect("/admin/user/manage");
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

        redirect("/admin/user/manage");
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

    public function components()
    {
        $this->view('user/components', ['title' => __('admin_panel')]);

    }
    public function bookingsSet($employeeId)
    {
//        vd($employeeId);
        $user = User::find((int)$employeeId);
        $errors = [];

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/users");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startDate = trim($_POST['startDate'] ?? '');
            $endDate = trim($_POST['endDate'] ?? '');
            $selectedList = $_POST['selected'] ?? [];
            $data = [];
            foreach ($selectedList as  $json_string) {
                $data = json_decode($json_string, true);
            }
            $date = $data['date'];
            $time = $data['time'];
            $serviceId = $data['service_id'];
            $employeeServiceId = $data['employeeServiceId'];
            //TODO dataye jadval ro tooye bazeye zamani baraye oon user_id va service ro agar status eshoon no action bood
            //TODO badesh miyaym dataye $selecttedList ro be jadval ezafe konam
            //TODO header e jadval ro yadet bashe
            if (empty($errors)) {
                $book = new PreBooking([
                    'user_id' => $employeeId,
                    'employee_service_id' => $employeeServiceId,
                    'time' => $time,
                    'date' => $date,
                    'status' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                if ($book->save()) {
                    vd($book);
                    clear_old_input();
                    $_SESSION['flash_success'] = __('register_success');
                    redirect("/admin/user/manage");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        } else {
            clear_old_input();
        }

        $salon = Salon::find(/*(int)$user->salon_id*/1);
        $holidays = Holiday::all();
        $userTypes = UserType::all();
        $employeeServicesData = EmployeeService::query()
            ->join('service_table AS srv','srv.id','=','employee_service_table.service_id')
            ->select(['employee_service_table.*', (APP_LANG === 'fa' ? 'srv.fa_title' : 'srv.en_title').' AS title'])
            ->where('user_id', '=', $employeeId)->get() ?? [];
        $i1 = intval(date('w')) - intval($salon->start_day_of_week);
        $todayIndex = $i1<0? $i1+7:$i1;
        $today = new DateTime('today');
        $deltaToWeekStart = $todayIndex;
        $weekStart = (clone $today)->modify("-{$deltaToWeekStart} days");
        $prebookingList = PreBooking::query()
            ->select(["employee_booking_list_table.*,est.service_id,srv.*"])
            ->join('employee_service_table AS est','est.id','=','employee_service_id')
            ->join('service_table AS srv','srv.id','=','est.service_id')
            ->where('employee_booking_list_table.user_id', '=', $employeeId)
            ->where('date', '>=', $weekStart->format('Y-m-d'))
            ->get();

        $this->view('admin/booking/set', [
            'title' => __('edit_user'),
            'user' => $user,
            'userTypes' => $userTypes,
            'salon' => $salon,
            'holidays' => $holidays,
            'prebookingList' => $prebookingList,
            'employeeServicesData' => $employeeServicesData,
        ]);
    }
    public function bookingsSettings()
    {
        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/users");
            exit;
        }

        $userTypes = UserType::all();
        $groupedServices = Service::groupedForSelect();
        $employeeServicesData = EmployeeService::query()->join('service_table AS srv','srv.id','=','employee_service_table.service_id')->select(['employee_service_table.*', (APP_LANG === 'fa' ? 'srv.fa_title' : 'srv.en_title').' AS title'])->where('user_id', '=', $id)->get() ?? [];
        $selectedServiceIds =[];
        $durations = Duration::all();

        foreach ($employeeServicesData as $service) {
            $selectedServiceIds[] = $service->service_id;
        }

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
    public function add()
    {
        $errors = [];
        $user = User::find($_SESSION['user_id']);
        $salonId = $_SESSION['salon_id'] ?? 0;
        $salon = Salon::find($salonId);
        $days = APP_LANG == "fa" ? ['یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه', 'شنبه'] : ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        $days = rotateArray($days, $salon->start_day_of_week);
        $durations = Duration::all();
        $service = Service::query()->where('parent_id', '<>', 0)->where("deleted", "=", 0)->get();
        $userRole = UserType::all();
        $lang = $_SESSION['lang'] ?? 'fa';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name']);
            $lastname = trim($_POST['last_name']);
            $nationalCode = trim($_POST['national_code']);
            $phoneNumber = trim($_POST['phoneNumber']);
            $postal_address = trim($_POST['address']);
            $role = trim($_POST['role']);
            $birth_date = trim($_POST['birth_date']);
            $serviceChosen = $_POST['service'] ?? [];
            $service_prices = $_POST['service_prices'] ?? [];
            $service_durations = $_POST['service_durations'] ?? [];
            $employeeHolidays = $_POST['holiday'] ?? [];
            $holiday = array_map(function ($value) {
                return ($value === "on") ? 1 : 0;
            }, $employeeHolidays);
            $startTime = $_POST['startTime'] ?? [];
            $endTime = $_POST['endTime'] ?? [];
            $followSalon = isset($_POST['followSalon']) && $_POST['followSalon'] === 'on' ? 1 : 0;
            $validator = new Validator($_POST, [
                'first_name' => 'required|min:2|max:40',
                'last_name' => 'required|min:2|max:40',
                'national_code' => 'required|min:2|max:40',
                'address' => 'required|min:1',
                'phoneNumber' => 'required|max:11',
                'service' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }
            if(!empty($phoneNumber)) {
                if (User::query()->where('phone_number', '=', $phoneNumber)->get()) {
                    $errors[] = __('phone_taken');
                    $_SESSION['flash_error'] = __('phone_taken');
                    save_old_input();
                }
            }
            if ($birth_date != "") {
                $miladiBirthDate = getMiladiBirthDate($birth_date);
            }

            if (empty($errors)) {
                $user = new User([
                    'salon_id' => 1,
                    'first_name' => $firstName,
                    'last_name' => $lastname,
                    'phone_number' => $phoneNumber,
                    'national_code' => $nationalCode,
                    'birth_date' => (($birth_date == "") ? '' : $miladiBirthDate),
                    'password' => password_hash("123456", PASSWORD_DEFAULT),
                    'postal_address' => $postal_address,
                    'register_datetime' => date('Y-m-d H:i:s'),
                    'user_type' => $role,
                    'follow_shift_from_salon' => $followSalon,
                    'deleted' => 0,
                    'is_active' => 1
                ]);

                if ($user->save()) {
                    $userId = $user->id;
                    if ($role == UserType::EMPLOYEE) {
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

                        if ($followSalon == 0) {
                            foreach ($days as $index => $day) {
                                $off = $holiday[$index] ?? 0;
                                $startTimeWork = $startTime[$index] ?? '00:00';
                                $endTimeWork = $endTime[$index] ?? '00:00';
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
                    }
                    clear_old_input();
                    $_SESSION['flash_success'] = __('register_success');
                    redirect("/admin/user/add");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }

        }

        $this->view('user/originalView/addUser', [
            'title' => __('add_user'),
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
            'lang' => $lang,
            'services' => $service,
            'days' => $days,
            'userRole' => $userRole,
            'user' => $user,
            'durations' => $durations,
            'errors' => $errors,
        ]);
    }
    public function manageUsers()
    {
        $lang = $_SESSION['lang'] ?? 'fa';
        $sortBy = isset($_GET['sortby']) ? $_GET['sortby'] : '';
        $filter = trim($_GET['filter'] ?? 'all');
        $sortOrder = isset($_GET['sortorder']) ? $_GET['sortorder'] : '';
        $sortFirstNameUrl = (BASE_URL . '/admin/user/manage?sortby=title&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');
        $sortLastNameUrl = (BASE_URL . '/admin/user/manage?sortby=category&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');
        $allowedPerPage = [1, 10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowedPerPage) ? (int)$_GET['per_page'] : 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

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
        $userType = UserType::all();
        $allUsers2 = User::getAllUserWithDetails();
        $customerData = User::getSomeUserWithDetails(UserType::CUSTOMER);
        $employeesData = User::getSomeUserWithDetails(UserType::EMPLOYEE);
        $operatorsData = User::getSomeUserWithDetails(UserType::OPERATOR);
        $adminData = User::getSomeUserWithDetails(UserType::ADMIN);
        $superAdminData = User::getSomeUserWithDetails(UserType::SUPER_ADMIN);
        $sortByColumn = "user_table.first_name";
        if (!empty($sortBy)) {
            if ($sortBy == 'first_name') {
                $sortByColumn = $sortBy;
            } else if ($sortBy == 'last_name') {
                $sortByColumn = 'last_name';
            } else if ($sortBy == 'user_role') {
                $sortByColumn = 'user_role';
            }
        }
        $allUsers2->orderBy($sortByColumn, $sortOrder);
        $allSearchData = User::getAllUserWithDetails();
        $users = User::getAllUserWithDetails();
        $customers = User::getSomeUserWithDetails(UserType::CUSTOMER);
        $employees = User::getSomeUserWithDetails(UserType::EMPLOYEE);
        $operators = User::getSomeUserWithDetails(UserType::OPERATOR);
        $admin = User::getSomeUserWithDetails(UserType::ADMIN);
        $superAdmin = User::getSomeUserWithDetails(UserType::SUPER_ADMIN);
        $column = $lang == "fa" ? "user_table.first_name" : 'user_table.first_name';

        if ($search !== '') {
            $allSearchData->whereLike($column, $search);
            $users->whereLike($column, $search);
            $admin->whereLike($column, $search);
            $superAdmin->whereLike($column, $search);
            $customers->whereLike($column, $search);
            $employees->whereLike($column, $search);
            $operators->whereLike($column, $search);
            $operatorsData->whereLike($column, $search);
            $customerData->whereLike($column, $search);
            $employeesData->whereLike($column, $search);
            $superAdminData->whereLike($column, $search);
            $adminData->whereLike($column, $search);
            $allUsers2->whereLike($column, $search);
        }
        if ($filter == "all" || empty($filter)) {
            $pagination = $allUsers2->paginate($page, $perPage);
        } else if ($filter == "customers") {
            $pagination = $customerData->paginate($page, $perPage);
        } else if ($filter == "employees") {
            $pagination = $employeesData->paginate($page, $perPage);
        } else if ($filter == "operators") {
            $pagination = $operatorsData->paginate($page, $perPage);
        } else if ($filter == "super-admin") {
            $pagination = $superAdminData->paginate($page, $perPage);
        } else if ($filter == "manager") {
            $pagination = $adminData->paginate($page, $perPage);
        }
        $searchSize = sizeof($allSearchData->get());
        $usersSize = sizeof($users->get());
        $customersSize = sizeof($customers->get());
        $employeesSize = sizeof($employees->get());
        $operatorsSize = sizeof($operators->get());
        $adminsSize = sizeof($admin->get());
        $superAdminsSize = sizeof($superAdmin->get());
        $totalPages = ceil($pagination['total'] / $perPage);
        $this->view('user/originalView/manageUsers', [
            'title' => __('manage_users'),
            'search' => $search,
            'page' => $totalPages,
            'lang' => $lang,
            'filter' => $filter,
            'users' => $pagination['data'],
            'allUsers' => $usersSize,
            'customersSize' => $customersSize,
            'employeesSize' => $employeesSize,
            'operatorsSize' => $operatorsSize,
            'adminsSize' => $adminsSize,
            'superAdminsSize' => $superAdminsSize,
            'searchSize' => $searchSize,
            'pagination' => $pagination,
            'per_page' => $perPage,
            'sortBy' => $sortBy,
            'userType' => $userType,
            'sortFirstNameUrl' => $sortFirstNameUrl,
            'sortLastNameUrl' => $sortLastNameUrl,
            'allowedPerPage' => $allowedPerPage,
            'renderPagination' => renderPagination($totalPages, $page, $perPage, $search, $sortBy, $sortOrder, $filter, $lang),
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
        ]);
    }
    public function editUser2( $id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/user/manage");
            exit;
        }
        $errors = [];
        $salonId = $_SESSION['salon_id'] ?? 0;
        $salon = Salon::find($salonId);
        $days =APP_LANG=="fa"? [ 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه','شنبه'] : [ 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI','SAT'];
        $days=rotateArray($days,$salon->start_day_of_week);
        $userTypes = UserType::all();
        $groupedServices = Service::groupedForSelect();
        $categoryService = Service::query()->where("parent_id" , "<>" , "0")->get();
        $employeeServicesData = EmployeeService::query()->join('service_table AS srv','srv.id','=','employee_service_table.service_id')->select(['employee_service_table.*', (APP_LANG === 'fa' ? 'srv.fa_title' : 'srv.en_title').' AS title'])->where('user_id', '=', $id)->get() ?? [];
        $durations = Duration::all();
        $selectedServiceIds = [];
        foreach ($employeeServicesData as $service) {
            $selectedServiceIds[] = $service->service_id;
        }
        $employeeTimeWorkData = EmployeeService::query()->select(['st.*'])->join('employee_table AS st','st.user_id','=','employee_service_table.user_id')->where('st.user_id', '=', $id)->get() ?? [];
        $employeeServicesData2 = EmployeeService::query()
            ->join('service_table AS srv', 'srv.id', '=', 'employee_service_table.service_id' ,'left')
            ->join('duration_table AS dur', 'dur.id', '=', 'employee_service_table.estimated_duration' , 'left')
            ->select([
                'employee_service_table.*',
                (APP_LANG === 'fa' ? 'srv.fa_title' : 'srv.en_title') . ' AS title',
                'dur.title as durationTitle , dur.id as duration_id',
            ])
            ->where('user_id', '=', $id)
            ->where('employee_service_table.deleted', '=', 0)
            ->get();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $nationalCode = trim($_POST['national_code'] ?? '');
            $birthDate = trim($_POST['birth_date'] ?? '');
            $phoneNumber = trim($_POST['phone_number'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $breakTime = trim($_POST['breakTime'] ?? '');
            $followSalon = isset($_POST['followSalon']) && $_POST['followSalon'] === 'on' ? 1 : 0;
            $selectedServiceIds = $_POST['services'] ?? [];
            $servicePrices = $_POST['service_prices'] ?? [];
            $serviceDuration = $_POST['service_durations'] ?? [];
            $holidayRaw = $_POST['holiday'] ?? [];
            $startTime = $_POST['startTime']?? [];
            $endTime = $_POST['endTime']?? [];
            $holiday = array_map(function ($value) {
                return ($value === "on") ? 1 : 0;
            }, $holidayRaw);
            $existingServiceIds = array_map(fn($item) => (int)$item->service_id, $employeeServicesData2);
            $selectedServiceIds = array_map('intval', $selectedServiceIds);
            $servicesToAdd = array_diff($selectedServiceIds, $existingServiceIds);
            $servicesToRemove = array_diff($existingServiceIds, $selectedServiceIds);
            $unchangedServices = array_intersect($existingServiceIds, $selectedServiceIds);
            if ($birthDate != "") {
                $miladiBirthDate = getMiladiBirthDate($birthDate);
            }
            if (!empty($phoneNumber)) {
                $exists = User::query()
                    ->where('phone_number', '=' , $phoneNumber)
                    ->where('id', '!=', $id)
                    ->get();

                if ($exists) {
                    $errors[] = __('phone_taken');
                    $_SESSION['flash_error'] = __('phone_taken');
                    save_old_input();
                }
            }
            $validator = new Validator($_POST, [
                'first_name' => 'required|min:2|max:40',
                'last_name' => 'required|min:2|max:40',
                'national_code' => 'required|min:2|max:40',
                'address' => 'required|min:1',
                'phone_number' => 'required|max:11',
            ]);
            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }
            if (empty($errors)) {
                $user->first_name = $firstName;
                $user->last_name = $lastName;
                $user->national_code = $nationalCode;
                $user->phone_number = $phoneNumber;
                $user->postal_address = $address;
                $user->birth_date = (($birthDate == "") ? '' : $miladiBirthDate);
                $user->update_time = date('Y-m-d H:i:s');
                if ($user->save()) {
                    if($user->user_type == UserType::EMPLOYEE){
                        $user->syncEmployeeServicesWithDetails2($unchangedServices , $servicesToAdd ,  $servicePrices, $serviceDuration);
                    } else if($followSalon == 0){
                        foreach ($days as $index => $day) {
                            $employeeTable = EmployeeTable::query()
                                ->where('user_id', '=', $id)
                                ->where('start_day_of_week', '=', $index)
                                ->first();
                            if (!empty($employeeTable)) {
                                $employeeTable->user_id = $id;
                                $employeeTable->start_day_of_week = $index;
                                $employeeTable->off_day = $holiday[$index] ;
                                $employeeTable->start_time = $startTime[$index] ?? '00:00';
                                $employeeTable->end_time = $endTime[$index] ?? '23:59';
                                $employeeTable->save();

                            }
                        }
                    }
                    $_SESSION['flash_success'] = __('user_updated');
                    redirect("/admin/user/manage");
                    exit;
                }
                else {
                    $errors[] = __('save_error');
                }
            }





        } else {
            clear_old_input();
        }
        $selectedServiceIds= [];
        foreach ($employeeServicesData2 as $service) {
            $selectedServiceIds[] = $service->service_id;
        }
        $timeByDay = [];
        foreach ($employeeTimeWorkData as $time) {
            $dayIndex = (int)$time->start_day_of_week;
            $timeByDay[$dayIndex] = [
                'start_time' => date('H:i', strtotime($time->start_time)),
                'end_time' => date('H:i', strtotime($time->end_time)),
                'off_day'   => $time->off_day
            ];
        }
        $timesForView = [];
        foreach ($days as $index => $day) {
            $timesForView[$index] = $timeByDay[$index] ?? [
                'start_time' => '',
                'end_time' => '',
                'off_day' => 0
            ];
        }
        $this->view('user/originalView/editUser', [
            'title' => __('edit_user'),
            'user' => $user,
            'userTypes' => $userTypes,
            'groupedServices' => $groupedServices,
            'selectedServiceIds' => $selectedServiceIds,
            'employeeServicesData' => $employeeServicesData,
            'durations' => $durations,
            'services' => $categoryService,
            'days' => $days,
            'timesForView' => $timesForView,
            'errors' => $errors,
            'employeeServices' => $employeeServicesData2,
        ]);
    }

}
