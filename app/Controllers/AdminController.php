<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Duration;
use App\Models\Service;
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
        $users = User::all();
        $this->view('admin/users', [
            'title' => __('users_list'),
            'users' => $users
        ]);
    }

    public function editUser($id)
    {
        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }

        $userTypes = UserType::all();
        $groupedServices =  Service::groupedForSelect();
        $selectedServiceIds = [];
        $employeeServicesData = [];
        $durations = Duration::all();

        if ($user->user_type == UserType::EMPLOYEE) {
            $employeeServiceModels = $user->getEmployeeServices();
            foreach ($employeeServiceModels as $empService) {
                $selectedServiceIds[] = $empService->service_id;
                $service = Service::find($empService->service_id);
                if ($service) {
                    $employeeServicesData[] = [
                        'id'       => $service->id,
                        'title'    => $service->fa_title,
                        'price'    => $empService->price,
                        'duration' => $empService->duration_id
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
            'employeeServicesData'=> $employeeServicesData,
            'durations' => $durations,
        ]);
    }

    public function updateUser($id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name  = $_POST['last_name'] ?? '';
            $is_active  = isset($_POST['is_active']) ? 1 : 0;

            $newServices = $_POST['employee_services'] ?? [];
            $servicePrices = $_POST['service_prices'] ?? [];

            $serviceDurations = $_POST['service_durations'] ?? [];

            foreach ($newServices as $sid) {
                if (!isset($servicePrices[$sid]) || !is_numeric($servicePrices[$sid])) {
                    $errors[] = "قیمت برای سرویس $sid الزامی است.";
                }
                if (!isset($serviceDurations[$sid]) || !is_numeric($serviceDurations[$sid])) {
                    $errors[] = "مدت زمان برای سرویس $sid الزامی است.";
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
                $user->last_name  = $last_name;
                $user->is_active  = $is_active;

                if ($user->save()) {
                    $user->syncEmployeeServicesWithDetails($newServices, $servicePrices,$serviceDurations);

                    $_SESSION['flash_success'] = __('user_updated');
                    header("Location: " . BASE_URL . "/admin/users");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }

            $this->view('admin/edit_user', [
                'title' => __('edit_user'),
                'user'  => $user,
                'errors'=> $errors
            ]);
        }
    }


    public function deleteUser($id)
    {
        $user = User::find((int)$id);
        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }
        if ($user->delete()) {
            $_SESSION['flash_success'] = __('user_deleted');
        } else {
            $_SESSION['flash_error'] = __('delete_failed');
        }
        header("Location: " . BASE_URL . "/admin/users");
        exit;
    }
}
