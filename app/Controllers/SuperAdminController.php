<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Duration;
use App\Models\Plan;
use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use App\Models\UserType;

class SuperAdminController extends Controller
{
    public function panel()
    {
        $this->view('sa/panel', ['title' => __('admin_panel')]);
    }

    public function userList()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        if (!in_array($perPage, $allowedPerPage, true)) {
            header("Location: ?page=1&per_page=10");
            exit;
        }
        $pagination = User::query()
            ->where('user_type','=', UserType::SUPER_ADMIN)
            ->orWhere('user_type','=', UserType::SUPPORT)
            ->orWhere('user_type','=', UserType::FINANCIAL_MANAGER)
            ->orWhere('user_type','=', UserType::FINANCIAL_USER)
            ->orWhere('user_type','=', UserType::SUPPORT_MANAGER)
            ->paginate($page, $perPage);

        $this->view('sa/users',
            ['title' => __('users_list'),
                'users' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage
            ]);
    }
    public function salonList()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        if (!in_array($perPage, $allowedPerPage, true)) {
            header("Location: ?page=1&per_page=10");
            exit;
        }
        $pagination = Salon::query()->paginate($page, $perPage);

        $this->view('sa/salons',
            ['title' => __('salon_list'),
                'salons' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage
            ]);
    }
    public function editUser($id)
    {
        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect();
            redirect("/admin/users");
            exit;
        }

        $userTypes = UserType::all();
        $groupedServices = Service::groupedForSelect();
        $selectedServiceIds = [];
        $employeeServicesData = [];
        $durations = Duration::all();

        if ($user->user_type == UserType::EMPLOYEE) {
            $employeeServiceModels = $user->getEmployeeServices();
            $lang = $_SESSION['lang'] ?? 'fa';
            foreach ($employeeServiceModels as $empService) {
                $service = Service::find($empService->service_id);
                if ($service) {
                    $title = ($lang === 'fa') ? $service->fa_title : $service->en_title;
                    $selectedServiceIds[] = $empService->service_id;
                    $employeeServicesData[] = (object)[
                        'id' => $empService->id,
                        'service_id' => $empService->service_id,
                        'user_id' => $empService->user_id,
                        'price' => $empService->price,
                        'free_hour' => $empService->free_hour,
                        'estimated_duration' => $empService->estimated_duration,
                        'title' => $title,
                    ];
                }
            }
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
    public function editSalon($id)
    {
        $salon = Salon::find((int)$id);
        $planeType = Plan::all();
        $errors = [];

        if (!$salon) {
            $_SESSION['flash_error'] = __('salon_not_found');
            redirect("/salons" , true);
            exit;
        }

        $this->view('sa/editSalon', [
            'title' => __('edit_salon'),
            'salon' => $salon,
            'planeType' => $planeType,
            'dayWeek' => weekDay(),
            'errors' => $errors,
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
            $user_type  = $_POST['user_type'] ?? $user->user_type;

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
                $user->user_type  = $user_type;

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
    public function updateSalon($id)
    {
        $salon = Salon::find((int)$id);
        if (!$salon) {
            $_SESSION['flash_error'] = __('salon_not_found');
            redirect("/salons" , true);
            exit;
        }

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salon_name = $_POST['salon_name'];
            $manager = $_POST['manager'];
            $manager_mobile = $_POST['manager_mobile'];
            $salon_username = $_POST['username'];
            $postal_address = $_POST['postal_address'];
            $salon_link = $_POST['link_name'];
            $plan = $_POST['plan'];
            $about_us = $_POST['about_us'];
            $start_day_of_week = $_POST['start_day_of_week'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $start_time_weekend_1 = $_POST['start_time_weekend_1'];
            $end_time_weekend_1 = $_POST['end_time_weekend_1'];
            $start_time_weekend_2 = $_POST['start_time_weekend_2'];
            $end_time_weekend_2 = $_POST['end_time_weekend_2'];
            $max_reserve_day = $_POST['max_reserve_day'];
            $start_time_holiday = $_POST['start_time_holidays'];
            $end_time_holiday = $_POST['end_time_holidays'];
            $activeHolidays =   isset($_POST['active_holidays']) && $_POST['active_holidays'] === 'on' ? 1 : 0;
            $active_weekend_2 = isset($_POST['active_weekend_2']) && $_POST['active_weekend_2'] === 'on' ? 1 : 0;
            $active_weekend_1 = isset($_POST['active_weekend_1']) && $_POST['active_weekend_1'] === 'on' ? 1 : 0;
            $manager_email = $_POST['manager_email'];
            $validator = new Validator($_POST, [
                'salon_name' => 'required|min:2|max:100',
                'manager' => 'required|min:2|max:100',
                'manager_mobile' => 'required|min:2|max:100',
                'username' => 'required|min:2|max:100',
                'postal_address' => 'required|min:2|max:100',
                'link_name' => 'required|min:2|max:100',
                'plan' => 'required|min:1|max:100',
                'about_us' => 'required|min:2|max:100',
                'start_day_of_week' => 'required|min:1|max:100',
                'start_time' => 'required|min:2|max:100',
                'end_time' => 'required|min:2|max:100',
                'start_time_weekend_1' => 'required|min:2|max:100',
                'end_time_weekend_1' => 'required|min:2|max:100',
                'start_time_weekend_2' => 'required|min:2|max:100',
                'end_time_weekend_2' => 'required|min:2|max:100',
                'max_reserve_day' => 'required|min:2|max:100',
                'start_time_holidays' => 'required|min:2|max:100',
                'end_time_holidays' => 'required|min:2|max:100',
//                'active_holidays' => 'required|min:0|max:100',
//                'active_weekend_2' => 'required|min:0|max:100',
//                'active_weekend_1' => 'required|min:0|max:100',
                'manager_email' => 'required|min:2|max:100',
            ]);
            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }
            if (empty($errors)) {
                $salon->name = $salon_name;
                $salon->manager_email = $manager_email;
                $salon->manager_mobile = $manager_mobile;
                $salon->postal_address = $postal_address;
                $salon->avatar = "a";
                $salon->manager = $manager;
                $salon->username = $salon_username;
                $salon->link_name = $salon_link;
                $salon->plan_id = (int)$plan;
                $salon->about_us = $about_us;
                $salon->start_day_of_week = (int)$start_day_of_week;
                $salon->start_time = $start_time;
                $salon->end_time = $end_time;
                $salon->start_time_weekend_1 = $start_time_weekend_1;
                $salon->start_time_weekend_2 = $start_time_weekend_2;
                $salon->end_time_weekend_1 = $end_time_weekend_1;
                $salon->end_time_weekend_2 = $end_time_weekend_2;
                $salon->max_reserve_day = $max_reserve_day;
                $salon->start_time_holidays = $start_time_holiday;
                $salon->end_time_holidays = $end_time_holiday;
                $salon->active_holidays =   $activeHolidays;
                $salon->active_weekend_2 =  $active_weekend_2;
                $salon->active_weekend_1 =  $active_weekend_1;


                if ($salon->save()) {
                    $_SESSION['flash_success'] = __('salon_update');
                    redirect("/salons" , true);
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }
        }
        else {
            clear_old_input();
        }
        $this->view('sa/editSalon', [
            'title' => __('edit_user'),
            'user' => $salon,
            'errors' => $errors
        ]);
//        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $first_name = $_POST['first_name'] ?? '';
//            $last_name = $_POST['last_name'] ?? '';
//            $is_active = isset($_POST['is_active']) ? 1 : 0;
//            $user_type  = $_POST['user_type'] ?? $user->user_type;
//
//            $newServices = [];
//            if ($user_type == UserType::EMPLOYEE) {
//                $newServices = $_POST['employee_services'] ?? [];
//            }
//            $servicePrices = $_POST['service_prices'] ?? [];
//
//            $serviceDurations = $_POST['service_durations'] ?? [];
//
//            foreach ($newServices as $sid) {
//                if (!isset($servicePrices[$sid]) || !is_numeric($servicePrices[$sid])) {
//                    $errors[] = sprintf(__('price_required_for_service'), $sid);
//                }
//                if (!isset($serviceDurations[$sid]) || !is_numeric($serviceDurations[$sid])) {
//                    $errors[] = sprintf(__('duration_required_for_service'), $sid);
//                }
//            }
//
//
//            if (strlen($first_name) < 2) {
//                $errors[] = __('first_name_short');
//            }
//
//            foreach ($newServices as $sid) {
//                if (!isset($servicePrices[$sid]) || $servicePrices[$sid] === '' || !is_numeric($servicePrices[$sid])) {
//                    $errors[] = __('price_required_for_service') . " (ID: $sid)";
//                }
//            }
//
//            if (empty($errors)) {
//                $user->first_name = $first_name;
//                $user->last_name = $last_name;
//                $user->is_active = $is_active;
//                $user->user_type  = $user_type;
//
//                if ($user->save()) {
//                    $user->syncEmployeeServicesWithDetails($newServices, $servicePrices, $serviceDurations);
//
//                    $_SESSION['flash_success'] = __('user_updated');
//                    redirect("/admin/users");
//                    exit;
//                } else {
//                    $errors[] = __('save_error');
//                }
//            }
//
//            $this->view('admin/editUser', [
//                'title' => __('edit_user'),
//                'user' => $user,
//                'errors' => $errors
//            ]);
//        }
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
    public function deleteSalon($id)
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

    public function createSalon(){
        $errors = [];
        $planeType = Plan::all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $salon_name = $_POST['salon_name'];
            $manager = $_POST['manager'];
            $manager_mobile = $_POST['manager_mobile'];
            $salon_username = $_POST['username'];
            $postal_address = $_POST['postal_address'];
            $salon_link = $_POST['link_name'];
            $plan = $_POST['plan'];
            $about_us = $_POST['about_us'];
            $start_day_of_week = $_POST['start_day_of_week'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];
            $start_time_weekend_1 = $_POST['start_time_weekend_1'];
            $end_time_weekend_1 = $_POST['end_time_weekend_1'];
            $start_time_weekend_2 = $_POST['start_time_weekend_2'];
            $end_time_weekend_2 = $_POST['end_time_weekend_2'];
            $max_reserve_day = $_POST['max_reserve_day'];
            $start_time_holiday = $_POST['start_time_holidays'];
            $end_time_holiday = $_POST['end_time_holidays'];
            $activeHolidays = $_POST['active_holidays'] ?? 0;
            $active_weekend_2 = $_POST['active_weekend_2'] ?? 0;
            $active_weekend_1 = $_POST['active_weekend_1'] ?? 0;
            $manager_email = $_POST['manager_email'];
            $validator = new Validator($_POST, [
                'salon_name' => 'required|min:2|max:100',
                'manager' => 'required|min:2|max:100',
                'manager_mobile' => 'required|min:2|max:100',
                'username' => 'required|min:2|max:100',
                'postal_address' => 'required|min:2|max:100',
                'link_name' => 'required|min:2|max:100',
                'plan' => 'required|min:1|max:100',
                'about_us' => 'required|min:2|max:100',
                'start_day_of_week' => 'required|min:1|max:100',
                'start_time' => 'required|min:2|max:100',
                'end_time' => 'required|min:2|max:100',
                'start_time_weekend_1' => 'required|min:2|max:100',
                'end_time_weekend_1' => 'required|min:2|max:100',
                'start_time_weekend_2' => 'required|min:2|max:100',
                'end_time_weekend_2' => 'required|min:2|max:100',
                'max_reserve_day' => 'required|min:2|max:100',
                'start_time_holidays' => 'required|min:2|max:100',
                'end_time_holidays' => 'required|min:2|max:100',
//                'active_holidays' => 'required|min:0|max:100',
//                'active_weekend_2' => 'required|min:0|max:100',
//                'active_weekend_1' => 'required|min:0|max:100',
                'manager_email' => 'required|min:2|max:100',
            ]);
            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }
            $salon = new Salon([
                'name' => $salon_name,
                'manager_email' => $manager_email,
                'manager_mobile' => $manager_mobile,
                'postal_address' => $postal_address,
                'avatar' => "?",
                'manager' => $manager,
                'username' => $salon_username,
                'link_name' => $salon_link,
                'plan_id' => (int)$plan,
                'about_us' => $about_us,
                'start_day_of_week' => (int)$start_day_of_week,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'start_time_weekend_1' => $start_time_weekend_1,
                'end_time_weekend_1' => $end_time_weekend_1,
                'start_time_weekend_2' => $start_time_weekend_2,
                'end_time_weekend_2' => $end_time_weekend_2,
                'max_reserve_day' => $max_reserve_day,
                'start_time_holidays' => $start_time_holiday,
                'end_time_holidays' => $end_time_holiday,
                'active_holidays' => $activeHolidays,
                'active_weekend_2' => $active_weekend_2,
                'active_weekend_1' => $active_weekend_1,
                'active' => 1,
                'deleted' => 0
            ]);
            if (empty($errors)) {
                if ($salon->save()) {
                    clear_old_input();
                    $_SESSION['flash_success'] = __('add_salon_message');
                    redirect("/salons");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        }
        else {
            clear_old_input();
        }
        $this->view('sa/createSalon', [
            'title' => __('create_salon'),
            'planeType' => $planeType,
            'errors' => $errors,
            'dayWeek' =>  weekDay()
        ]);

    }

}
