<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Booking;
use App\Models\Duration;
use App\Models\EmployeeBookingListTable;
use App\Models\EmployeeService;
use App\Models\EmployeeTable;
use App\Models\PreBooking;
use App\Models\ServiceVisitRelation;
use App\Models\User;
use App\Models\Service;
use App\Models\UserType;
use App\Models\VisitTable;

class AdminBookingController extends Controller
{
    public function index()
    {
//        $bookings = Booking::query()
//            ->select([
//                'visit_table.id',
//                'user_table.first_name AS customer_first_name',
//                'user_table.last_name AS customer_last_name',
//                'employee.first_name AS employee_first_name',
//                'employee.last_name AS employee_last_name',
//                'service_table.fa_title AS service_title',
//                'visit_table.visit_datetime',
//                'visit_table.visit_status'
//            ])
//            ->join('user_table', 'visit_table.registrant_user_id', '=', 'user_table.id')
//            ->join('user_table AS employee', 'visit_table.employee_id', '=', 'employee.id')
//            ->join('service_table', 'visit_table.service_id', '=', 'service_table.id')
//            ->join('service_visit_relation_table AS serv', 'serv.visit_id', '=', 'visit_table.id')
//            ->orderBy('visit_table.start_time', 'DESC')
//            ->get();
        $bookings = Booking::query()
            ->select([
                'visit_table.id AS visit_id',
                'cu.first_name AS customer_first_name',
                'cu.last_name AS customer_last_name',
                'e.first_name AS employee_first_name',
                'cu.phone_number AS phone_number',
                'e.last_name AS employee_last_name',
                's.fa_title AS service_title',
                'visit_table.visit_datetime',
                'vs.fa_title AS visit_status'
            ])
            ->join('user_table AS cu', 'visit_table.customer_id', '=', 'cu.id')
            ->join('service_visit_relation_table AS svr', 'svr.visit_id', '=', 'visit_table.id')
            ->join('user_table AS e', 'svr.employee_id', '=', 'e.id')
            ->join('service_table AS s', 'svr.service_id', '=', 's.id')
            ->join('visit_status_table AS vs', 'svr.visit_status', '=', 'vs.id')
            ->orderBy('visit_table.visit_datetime', 'DESC')
            ->get();


        $this->view('admin/booking/list', [
            'title' => __('booking_list'),
            'bookings' => $bookings
        ]);
    }


    public function create()
    {
        $customers = User::query()->where('user_type', '=', UserType::CUSTOMER)->get();
        $employees = User::query()->where('user_type', '=', UserType::EMPLOYEE)->get();
        $services = Service::all();
        $durations = Duration::all();
        $this->view('admin/booking/new', [
            'title' => __('new_booking'),
            'customers' => $customers,
            'employees' => $employees,
            'services' => $services,
            'durations' => $durations,
        ]);
    }

    public function searchUser($phone)
    {
        header('Content-Type: application/json');
        $getUserByPhone = User::getUserDataByPhone($phone);
        echo json_encode([
            'success' => true,
            'data' => $getUserByPhone
        ]);
    }

    public function createReserve()
    {
        $errors = [];
        $employees = User::query()->where('user_type', '=', UserType::EMPLOYEE)->get();
        $services = Service::query()->where('parent_id', '<>', 0)->get();
        $durations = Duration::all();
        $lang = $_GET['lang'] ?? 'fa';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchPhone = $_POST['search'] ?? '';
            $phoneNumber = $_POST['phone_number_hidden'] ?? '';
            $fnHidden = $_POST['first_name_hidden'] ?? '';
            $flHidden = $_POST['last_name_hidden'] ?? '';
            $newFirstName = $_POST['first_name_modal'] ?? '';
            $newLastName = $_POST['last_name_modal'] ?? '';
            $serviceId = $_POST['service'] ?? '';
            $employeeId = $_POST['employee'] ?? '';
            $time = $_POST['time'] ?? 0;
            $date = $_POST['date'] ?? 0;
            $registrant = $_SESSION['user_id'] ?? '';
            $finalPhone = empty($searchPhone) ? $phoneNumber : $searchPhone;
            $ServiceFinalReservationModal = Service::query()->select([(APP_LANG == "fa" ? "fa_title" : "en_title") . " AS title"])->where("id", "=", $serviceId)->where("deleted", "=", "0")->first();
            $EmployeeFinalReservationModal = User::query()->where("id", "=", $employeeId)->where("deleted", "=", "0")->first();
            $checkedUser = User::query()->where('phone_number', '=', $finalPhone)->first();
            if ($checkedUser) {
                if ($checkedUser->deleted == 1) {
                        $checkedUser->deleted = 0;
                        $checkedUser->first_name = $newFirstName;
                        $checkedUser->last_name = $newLastName;
                        if (!$checkedUser->save()) {
                            $errors[] = __('user_save_error');
                        } else {
                            clear_old_input();
                        }
                }

            }
            else {
                $user = new User([
                    'first_name' => $newFirstName,
                    'last_name' => $newLastName,
                    'birth_date' => '',
                    'phone_number' => $phoneNumber,
                    'register_datetime' => date('Y-m-d H:i:s'),
                    'password' => '123456',
                    'salon_id' => 1,
                    'postal_address' => '',
                    'national_code' => '',
                    'has_launch_time' => '',
                    'launch_time' => '',
                    'has_dinner_time' => '',
                    'dinner_time' => '',
                    'follow_shift_from' => '',
                    'user_type' => UserType::CUSTOMER,
                    'is_active' => 1,
                    'deleted' => 0
                ]);

                if (empty($errors) && !$user->save()) {
                    $errors[] = __('user_save_error');
                }
            }
            $validator = new Validator($_POST, [
                'time' => 'required|min:1|max:40',
                'first_name_hidden' => 'required|min:1|max:40',
                'last_name_hidden' => 'required|min:1|max:40',
            ]);
            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }
            if (empty($errors)) {
                $getVisitDate = PreBooking::query()->where("id", "=", $time)->first();
                $dateTime = $date . " " . (($getVisitDate) ? $getVisitDate->time : '');
                $customerId = $checkedUser ? $checkedUser->id : $user->id;

                $visit = new VisitTable([
                    'registrant_user_id' => $registrant,
                    'customer_id' => $customerId,
                    'salon_id' => 1,
                    'visit_datetime' => $dateTime,
                    'register_datetime' => date('Y-m-d H:i:s'),
                    'note' => '',
                    'deleted' => 0
                ]);
                if ($visit->save()) {
                    $relation = new ServiceVisitRelation([
                        'registrant_user_id' => $registrant,
                        'visit_id' => $visit->id,
                        'service_id' => $serviceId,
                        'price' => 25000,
                        'initial_payment' => 100,
                        'payment_status' => 1,
                        'visit_status' => 1,
                        'employee_id' => $employeeId,
                        'deleted' => 0,
                    ]);

                    if ($relation->save()) {
                        $getVisitDate->status = 1;
                        $_SESSION['flash_success'] = __('booking_success');
                        $getVisitDate->save();
                    }

                    $_SESSION['reservation_success'] = true;

                    $_SESSION['reservation_data'] = [
                        'fullName' =>
                            (empty($newFirstName) && empty($newLastName))
                                ? ($fnHidden . " " . $flHidden)
                                : ($newFirstName . " " . $newLastName),

                        'service' => $ServiceFinalReservationModal->title,
                        'employee' => $EmployeeFinalReservationModal->first_name . " " . $EmployeeFinalReservationModal->last_name,
                        'dateTime' => $dateTime
                    ];
                }
            }
        }
        $showReservationModal = false;
        $reservationData = [];

        if (!empty($_SESSION['reservation_success'])) {
            $showReservationModal = true;
            $reservationData = $_SESSION['reservation_data'] ?? [];

            unset($_SESSION['reservation_success']);
            unset($_SESSION['reservation_data']);
        }


        $this->view('admin/booking/newBooking', [
            'title' => __('new_booking'),
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
            'employees' => $employees,
            'services' => $services,
            'durations' => $durations,
            'lang' => $lang,
            'errors' => $errors,
            'showReservationModal' => $showReservationModal,
            'reservationData' => $reservationData,
        ]);
    }

    public function getServiceId($id)
    {
        header('Content-Type: application/json');
        $getEmployeeService = EmployeeBookingListTable::getEmployeeService((int)$id);
        echo json_encode([
            'success' => true,
            'data' => $getEmployeeService
        ]);

    }

    public function getEmployeeTime($id)
    {
        header('Content-Type: application/json');
        $getEmployeeTime = PreBooking::getEmployeeTime($id);
        echo json_encode([
            'success' => true,
            'data' => $getEmployeeTime
        ]);
    }

    public function getEmployeeDate($id , $serviceId)
    {
        header('Content-Type: application/json');
        $getEmployeeTime = PreBooking::getEmployeeDate((int)$id , (int)$serviceId);
        echo json_encode([
            'success' => true,
            'data' => $getEmployeeTime
        ]);
    }

    public function addUser()
    {
        header('Content-Type: application/json');
        $phone = $_POST['phone_number_modal'] ?? '';
        $firstName = $_POST['first_name_modal'] ?? '';
        $lastName = $_POST['last_name_modal'] ?? '';
        if (!$phone || !$firstName || !$lastName) {
            echo json_encode([
                'success' => false,
                'message' => 'تمام فیلدها الزامی هستند.'
            ]);
            return;
        }
        $user = new User([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birth_date' => "",
            'phone_number' => $phone,
            'register_datetime' => date('Y-m-d H:i:s'),
            'password' => "123456",
            'salon_id' => 1,
            'postal_address' => '',
            'national_code' => '',
            'has_launch_time' => '',
            'launch_time' => '',
            'has_dinner_time' => '',
            'dinner_time' => '',
            'follow_shift_from' => '',
            'user_type' => UserType::CUSTOMER,
            'is_active' => 1,
            'deleted' => 0
        ]);

        if ($user->save()) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone_number' => $user->phone_number
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'خطا در ذخیره کاربر.'
            ]);
        }
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $visit = Booking::create([
                'registrant_user_id' => $request['registrant_user_id'],
                'customer_id' => $request['customer_id'],
                'visit_datetime' => $request['visit_datetime'],
                'register_datetime' => date('Y-m-d H:i:s'),
                'note' => $request['note'] ?? '',
                'deleted' => 0,
            ]);

            // 2. ایجاد رکوردهای relation برای هر سرویس انتخاب شده
            foreach ($request['services'] as $service) {
                ServiceVisitRelation::create([
                    'visit_id' => $visit->id,
                    'service_id' => $service['service_id'],
                    'price' => $service['price'],
                    'initial_payment' => $service['initial_payment'] ?? 0,
                    'payment_status' => $service['payment_status'], // باید id از payment_status_table باشه
                    'visit_status' => $service['visit_status'],   // باید id از visit_status_table باشه
                    'employee_id' => $service['employee_id'],
                    'deleted' => 0,
                ]);
            }

            if ($booking->save()) {
                $_SESSION['flash_success'] = __('booking_created');
                redirect("/admin/bookings");
                exit;
            } else {
                $_SESSION['flash_error'] = __('save_error');
            }
        }

        redirect("/admin/bookings/new");
    }

    public function storeBooking()
    {
        $employeeId = $_POST['employee_id'] ?? null;
        $customerId = $_POST['customer_id'] ?? null;
        $serviceId = $_POST['service_id'] ?? null;
        $date = $_POST['date'] ?? null;
        $time = $_POST['time'] ?? null;

        if (!$employeeId || !$customerId || !$serviceId || !$date || !$time) {
            $_SESSION['flash_error'] = __('fill_all_fields');
            redirect("/admin/bookings/new");
            exit;
        }

        $employee = User::query()
            ->where('id', '=', $employeeId)
            ->where('user_type', '=', UserType::EMPLOYEE)
            ->first();

        if (!$employee) {
            $_SESSION['flash_error'] = __('invalid_employee');
            redirect("/admin/bookings/new");
            exit;
        }

        $customer = User::query()
            ->where('id', '=', $customerId)
            ->where('user_type', '=', UserType::CUSTOMER)
            ->first();

        if (!$customer) {
            $_SESSION['flash_error'] = __('invalid_customer');
            redirect("/admin/bookings/new");
            exit;
        }

        $employeeService = EmployeeService::query()
            ->where('user_id', '=', $employeeId)
            ->where('service_id', '=', $serviceId)
            ->first();

        if (!$employeeService) {
            $_SESSION['flash_error'] = __('service_not_assigned_to_employee');
            redirect("/admin/bookings/new");
            exit;
        }

        $reservedAt = $date . ' ' . $time;
        $conflict = Booking::query()
            ->where('employee_id', '=', $employeeId)
            ->where('reserved_at', '=', $reservedAt)
            ->first();

        if ($conflict) {
            $_SESSION['flash_error'] = __('time_slot_already_reserved');
            redirect("/admin/bookings/new");
            exit;
        }

        $booking = new Booking([
            'employee_id' => $employeeId,
            'customer_id' => $customerId,
            'service_id' => $serviceId,
            'reserved_at' => $reservedAt,
        ]);

        if ($booking->save()) {
            $_SESSION['flash_success'] = __('booking_saved');
        } else {
            $_SESSION['flash_error'] = __('save_error');
        }

        redirect("/admin/bookings/new");
        exit;
    }

    public function getEmployeeServices($employeeId)
    {
        $employee = User::query()
            ->where('id', '=', $employeeId)
            ->where('user_type', '=', UserType::EMPLOYEE)
            ->first();

        if (!$employee) {
            http_response_code(403);
            echo json_encode(['error' => 'invalid employee']);
            exit;
        }

        $services = Service::query()
            ->select(['service_table.id', 'service_table.fa_title', 'employee_service_table.estimated_duration'])
            ->join('employee_service_table', 'employee_service_table.service_id', '=', 'service_table.id')
            ->where('employee_service_table.user_id', '=', $employeeId)
            ->get();

        header('Content-Type: application/json');
        echo json_encode(array_map(fn($s) => $s->toArray(), $services));
        exit;
    }

    public function getServiceDuration($employeeId, $serviceId)
    {


        $employee = User::query()
            ->where('id', '=', $employeeId)
            ->where('user_type', '=', UserType::EMPLOYEE)
            ->first();

        if (!$employee) {
            http_response_code(403);
            echo json_encode(['error' => 'invalid employee']);
            exit;

        }

        $es = EmployeeService::query()
            ->where('user_id', '=', $employeeId)
            ->where('service_id', '=', $serviceId)
            ->first();

        if (!$es) {
            http_response_code(403);
            echo json_encode(['error' => 'service not found for this employee']);
            exit;
        }

        $result = [];
        $minutes = $es->estimated_duration;
        $hh = str_pad(floor($minutes / 60), 2, '0', STR_PAD_LEFT);
        $mm = str_pad($minutes % 60, 2, '0', STR_PAD_LEFT);
        $result['estimated_duration_hhmm'] = $hh . ':' . $mm;

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function weeklySchedule($employeeId)
    {
        $startDate = $_GET['start'] ?? date('Y-m-d');

        $employee = User::query()
            ->where('id', '=', $employeeId)
            ->where('user_type', '=', UserType::EMPLOYEE)
            ->first();

        if (!$employee) {
            http_response_code(403);
            echo json_encode(['error' => 'invalid employee']);
            exit;
        }

        $services = Service::query()
            ->select(['service_table.id', 'service_table.fa_title', 'employee_service_table.estimated_duration'])
            ->join('employee_service_table', 'employee_service_table.service_id', '=', 'service_table.id')
            ->where('employee_service_table.user_id', '=', $employeeId)
            ->get();

        header('Content-Type: application/json');
        echo json_encode(array_map(fn($s) => $s->toArray(), $services));
        exit;
    }

    public function updateStatusType($id)
    {
        header('Content-Type: application/json');
        $errors = [];

        $service = Service::find($id);
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

        // به‌روزرسانی اطلاعات
        $service->fa_title = $editFaTitle;
        $service->en_title = $editEnTitle;
        $service->service_key = "";

        if ($isCategory === 1) {
            // خودش دسته است
            $service->parent_id = 0;
        } else {
            // زیرمجموعه یک دسته دیگر است
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

}
