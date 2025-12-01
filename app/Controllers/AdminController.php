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
    public function editUser2($id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/users");
            exit;
        }
        $days =APP_LANG=="fa"? [ 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه','شنبه'] : [ 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI','SAT'];
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

        $timeByDay = [];
        foreach ($employeeTimeWorkData as $time) {
            $timeByDay[$time->start_day_of_week] = [
                'start_time' => substr($time->start_time, 0, 5),
                'end_time' => substr($time->end_time, 0, 5),
                'off_day' => $time->off_day
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
                    'status' => 1,
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

}
