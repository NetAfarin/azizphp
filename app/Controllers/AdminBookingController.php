<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Booking;
use App\Models\Duration;
use App\Models\EmployeeService;
use App\Models\User;
use App\Models\Service;
use App\Models\UserType;

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



        $this->view('admin/booking/index', [
            'title' => __('booking_list'),
            'bookings' => $bookings
        ]);
    }


    public function create()
    {
        $customers = User::query()->where('user_type', '=', UserType::CUSTOMER)->get();
        $employees = User::query()->where('user_type', '=', UserType::EMPLOYEE)->get();
        $services  = Service::all();
        $durations = Duration::all();
        $this->view('admin/booking/new', [
            'title' => __('new_booking'),
            'customers' => $customers,
            'employees' => $employees,
            'services' => $services,
            'durations' => $durations,
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $visit = Booking::create([
                'registrant_user_id' => $request['registrant_user_id'],
                'customer_id'        => $request['customer_id'],
                'visit_datetime'     => $request['visit_datetime'],
                'register_datetime'  => date('Y-m-d H:i:s'),
                'note'               => $request['note'] ?? '',
                'deleted'            => 0,
            ]);

            // 2. ایجاد رکوردهای relation برای هر سرویس انتخاب شده
            foreach ($request['services'] as $service) {
                ServiceVisitRelation::create([
                    'visit_id'        => $visit->id,
                    'service_id'      => $service['service_id'],
                    'price'           => $service['price'],
                    'initial_payment' => $service['initial_payment'] ?? 0,
                    'payment_status'  => $service['payment_status'], // باید id از payment_status_table باشه
                    'visit_status'    => $service['visit_status'],   // باید id از visit_status_table باشه
                    'employee_id'     => $service['employee_id'],
                    'deleted'         => 0,
                ]);
            }

            if ($booking->save()) {
                $_SESSION['flash_success'] = __('booking_created');
                header("Location: " . BASE_URL . "/admin/bookings");
                exit;
            } else {
                $_SESSION['flash_error'] = __('save_error');
            }
        }

        header("Location: " . BASE_URL . "/admin/bookings/new");
    }
    public function storeBooking()
    {
        $employeeId = $_POST['employee_id'] ?? null;
        $customerId = $_POST['customer_id'] ?? null;
        $serviceId  = $_POST['service_id'] ?? null;
        $date       = $_POST['date'] ?? null;
        $time       = $_POST['time'] ?? null;

        if (!$employeeId || !$customerId || !$serviceId || !$date || !$time) {
            $_SESSION['flash_error'] = __('fill_all_fields');
            header("Location: " . BASE_URL . "/admin/bookings/new");
            exit;
        }

        $employee = User::query()
            ->where('id', '=', $employeeId)
            ->where('user_type', '=', UserType::EMPLOYEE)
            ->first();

        if (!$employee) {
            $_SESSION['flash_error'] = __('invalid_employee');
            header("Location: " . BASE_URL . "/admin/bookings/new");
            exit;
        }

        $customer = User::query()
            ->where('id', '=', $customerId)
            ->where('user_type', '=', UserType::CUSTOMER)
            ->first();

        if (!$customer) {
            $_SESSION['flash_error'] = __('invalid_customer');
            header("Location: " . BASE_URL . "/admin/bookings/new");
            exit;
        }

        $employeeService = EmployeeService::query()
            ->where('user_id', '=', $employeeId)
            ->where('service_id', '=', $serviceId)
            ->first();

        if (!$employeeService) {
            $_SESSION['flash_error'] = __('service_not_assigned_to_employee');
            header("Location: " . BASE_URL . "/admin/bookings/new");
            exit;
        }

        $reservedAt = $date . ' ' . $time;
        $conflict = Booking::query()
            ->where('employee_id', '=', $employeeId)
            ->where('reserved_at', '=', $reservedAt)
            ->first();

        if ($conflict) {
            $_SESSION['flash_error'] = __('time_slot_already_reserved');
            header("Location: " . BASE_URL . "/admin/bookings/new");
            exit;
        }

        $booking = new Booking([
            'employee_id' => $employeeId,
            'customer_id' => $customerId,
            'service_id'  => $serviceId,
            'reserved_at' => $reservedAt,
        ]);

        if ($booking->save()) {
            $_SESSION['flash_success'] = __('booking_saved');
        } else {
            $_SESSION['flash_error'] = __('save_error');
        }

        header("Location: " . BASE_URL . "/admin/bookings/new");
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
            ->select(['service_table.id', 'service_table.fa_title','employee_service_table.estimated_duration'])
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

}
