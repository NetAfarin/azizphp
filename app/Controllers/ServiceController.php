<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Duration;
use App\Models\Service;
use App\Models\User;
use App\Models\UserType;
use http\Exception\UnexpectedValueException;

class ServiceController extends Controller
{
    public function management()
    {
        $services = Service::query()->where("parent_id", "<>", 0)->get();
        $categories = Service::query()->where("parent_id", "=", 0)->get();

        $this->view('admin/services/dashboard', [
            'title' => __('manage_services'),
            'categoryCount' => sizeof($categories),
            'services' => sizeof($services)
        ]);
    }

    public function categories()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];

        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        if (!in_array($perPage, $allowedPerPage, true)) {
            header("Location: ?page=1&per_page=10");
            exit;
        }
        $pagination = Service::query()->where('parent_id', '=', 0)->where('deleted', '=', 0)->paginate($page, $perPage);

        $categories = $pagination['data'];

        foreach ($categories as $category) {
            $category->subCategoriesCount = sizeof(Service::query()->where('parent_id', '=', $category->id)->get());
        }

        $this->view('admin/services/categories', [
            'title' => __('services'),
            'categories' => $categories,
            'pagination' => $pagination,
            'per_page' => $perPage,
            'allowedPerPage' => $allowedPerPage
        ]);
    }

    public function addCategory()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fa_title = trim($_POST['fa_title'] ?? '');
            $en_title = trim($_POST['en_title'] ?? '');
            $service_key = trim($_POST['service_key'] ?? '');

            $validator = new Validator($_POST, [
                'fa_title' => 'required|min:2|max:40',
                'en_title' => 'required|min:2|max:40',
                'service_key' => 'required|min:2|max:40',
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }

            $category = new Service([
                'service_key' => $service_key,
                'fa_title' => $fa_title,
                'en_title' => $en_title,
                'parent_id' => 0,
                'deleted' => 0,
            ]);
            if (empty($errors)) {
                if ($category->save()) {
                    clear_old_input();
                    $_SESSION['flash_success'] = __('add_category_message');
                    header("Location: " . BASE_URL . "/admin/services/categories");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        }
        else {
            clear_old_input();
        }
        $this->view('admin/services/addCategory', [
            'errors' => $errors,
            'title' => __('services')
        ]);
    }

    public function editCategory($id)
    {

        $service = Service::find((int)$id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($errors)) {
                $service->fa_title = $_POST['fa_title'];
                $service->en_title = $_POST['en_title'];
                $service->service_key = $_POST['service_key'];

                if ($service->save()) {
                    $_SESSION['flash_success'] = __('category_update');
                    header("Location: " . BASE_URL . "/admin/services/categories");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }
        } else {
            $categories = Service::query()->where("id", "=", $id)->first();
            $this->view('admin/services/editCategories', [
                'title' => __('edit_user'),
                'categories' => $categories
            ]);
        }

    }

    public function deleteCategory($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        $category = Service::find((int)$id);
        if (!$category) {
            $_SESSION['flash_error'] = __('category_not_found');
            header("Location: " . BASE_URL . "/admin/services/categories");
            exit;
        }


        if (property_exists($category, 'deleted')) {
            $category->deleted = 1;
            $success = $category->save();
        } else {
            $success = $category->delete();
        }

        if ($success) {
            $_SESSION['flash_success'] = __('delete_category_message');
            Logger::info("Category {$category->id} deleted by admin {$_SESSION['user_id']}");
        } else {
            $_SESSION['flash_error'] = __('delete_failed');
        }

        header("Location: " . BASE_URL . "/admin/services/categories");
        exit;
    }

    public function services()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        if (!in_array($perPage, $allowedPerPage, true)) {
            header("Location: ?page=1&per_page=10");
            exit;
        }

        $pagination = Service::query()
            ->where("parent_id", "<>", 0)
            ->where("deleted", "=", 0)
            ->paginate($page, $perPage);

        $services = $pagination['data'];

        $this->view('admin/services/subCategoriesList', [
            'title' => __('services'),
            'services' => $services,
            'pagination' => $pagination,
            'per_page' => $perPage,
            'allowedPerPage' => $allowedPerPage
        ]);
    }

    public function addService()
    {
        $services = Service::query()->where("parent_id", "=", 0)->get();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fa_title = $_POST['fa_title'];
            $en_title = $_POST['en_title'];
            $service_key = $_POST['service_key'];
            $categoryId= $_POST['parent_id'];
            $service = new Validator($_POST, [
                'service_key' => "required|min:2|max:40",
                'fa_title' => "required|min:2|max:40",
                'en_title' => "required|min:2|max:40",
                'parent_id' => "required|min:1|max:100",
                'deleted' => 0,
            ]);
            if ($service->fails()) {
                $errors = array_merge($errors, $service->errors());
                save_old_input();
            }
            $category = new Service([
                'service_key' => $service_key,
                'fa_title' => $fa_title,
                'en_title' => $en_title,
                'parent_id' => $categoryId,
                'deleted' => 0,
            ]);
            if (empty($errors)) {

                if ($category->save()) {

                    clear_old_input();
                    $_SESSION['flash_success'] = __('add_category_message');
                    header("Location: " . BASE_URL . "/admin/services");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        }
        else {
            clear_old_input();
        }
        $this->view('admin/services/addService', [
            'errors' => $errors,
            'title' => __('services'),
            "services" => $services
        ]);

    }

    public function editService($id)
    {
        $service = Service::find((int)$id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($errors)) {
                $service->fa_title = $_POST['fa_title'];
                $service->en_title = $_POST['en_title'];
                $service->service_key = $_POST['service_key'];
                $service->parent_id = $_POST['parent_id'];

                if ($service->save()) {
                    $_SESSION['flash_success'] = __('category_update');
                    header("Location: " . BASE_URL . "/admin/services/categories");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }
        } else {
            $allCategory = Service::query()
                ->where("parent_id", "=", 0)
                ->get();

            $this->view('admin/services/editService', [
                'title' => __('edit_user'),
                'categories' => $allCategory,
                'service' => $service
            ]);
        }
    }

    public function deleteService($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }

        $category = Service::find((int)$id);
        if (!$category) {
            $_SESSION['flash_error'] = __('category_not_found');
            header("Location: " . BASE_URL . "/admin/services");
            exit;
        }


        if (property_exists($category, 'deleted')) {
            $category->deleted = 1;
            $success = $category->save();
        } else {
            $success = $category->delete();
        }

        if ($success) {
            $_SESSION['flash_success'] = __('delete_sub_category_message');
            Logger::info("Service {$category->id} deleted by admin {$_SESSION['user_id']}");
        } else {
            $_SESSION['flash_error'] = __('delete_failed');
        }

        header("Location: " . BASE_URL . "/admin/services");
        exit;
    }

}