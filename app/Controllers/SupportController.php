<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Models\Duration;
use App\Models\Service;
use App\Models\User;
use App\Models\UserType;

class SupportController extends Controller
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
        $pagination = User::query()->paginate($page, $perPage);

        $this->view('admin/users',
            ['title' => __('users_list'),
                'users' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage
            ]);
    }
    public function ticketList()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        if (!in_array($perPage, $allowedPerPage, true)) {
            header("Location: ?page=1&per_page=10");
            exit;
        }
        $pagination = User::query()->paginate($page, $perPage);

        $this->view('admin/users',
            ['title' => __('users_list'),
                'users' => $pagination['data'],
                'pagination' => $pagination,
                'per_page' => $perPage,
                'allowedPerPage' => $allowedPerPage
            ]);
    }
    public function addTicket($id)
    {
        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            redirect("/admin/users");
            exit;
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt', 'zip'];
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            // خطای نامعتبر بودن فرمت
        }

        $userTypes = UserType::all();
        $groupedServices = Service::groupedForSelect();
        $selectedServiceIds = [];
        $employeeServicesData = [];
        $durations = Duration::all();

        // فایل‌هایی که می‌خواهیم اجازه بارگذاری داشته باشیم
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'application/zip'
        ];

        // مسیر ذخیره فایل‌ها
        $uploadDirectory = 'uploads/tickets/';  // مسیر فایل‌های بارگذاری شده

        // بررسی ارسال فایل
        if (isset($_FILES['attachment'])) {
            $file = $_FILES['attachment'];
            $fileName = $file['name'];
            $fileTmpPath = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            // بررسی اینکه فایل به درستی آپلود شده است
            if ($fileError === UPLOAD_ERR_OK) {
                // بررسی نوع MIME فایل
                $fileMimeType = mime_content_type($fileTmpPath);
                if (!in_array($fileMimeType, $allowedMimeTypes)) {
                    $_SESSION['flash_error'] = __('invalid_file_type');
                    redirect();
                    exit;
                }

                // بررسی اندازه فایل (مثلاً 5MB)
                $maxFileSize = 5 * 1024 * 1024;
                if ($fileSize > $maxFileSize) {
                    $_SESSION['flash_error'] = __('file_size_exceeded');
                    redirect();
                    exit;
                }

                // تغییر نام فایل به صورت یکتا (برای جلوگیری از تداخل)
                $newFileName = uniqid() . '-' . basename($fileName);
                $destination = $uploadDirectory . $newFileName;

                // ایجاد دایرکتوری در صورتی که وجود نداشته باشد
                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0777, true);
                }

                // انتقال فایل به مسیر مقصد
                if (move_uploaded_file($fileTmpPath, $destination)) {
                    // ذخیره مسیر فایل در دیتابیس یا هر کاری که نیاز دارید
                    // برای مثال: ذخیره در جدول tickets (یا جدول مرتبط)
                    // Ticket::create([...]);

                    $_SESSION['flash_success'] = __('file_uploaded_successfully');
                } else {
                    $_SESSION['flash_error'] = __('file_upload_failed');
                    redirect();
                    exit;
                }
            } else {
                $_SESSION['flash_error'] = __('file_upload_error');
                redirect();
                exit;
            }
        }

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

    public function editTicket($id)
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

    public function updateTicket($id)
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


    public function deleteTicket($id)
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


}
