<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Service;
use App\Models\User;
use function Sodium\add;

class ServiceController extends Controller
{
    public function management()
    {
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $sortBy = isset($_GET['sortby']) ? $_GET['sortby'] : '';
        $sortOrder = isset($_GET['sortorder']) ? $_GET['sortorder'] : '';

        $sortTitleUrl = (BASE_URL . '/admin/services/management?sortby=title&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');
        $sortCategoryUrl = (BASE_URL . '/admin/services/management?sortby=category&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');

        $allowedPerPage = [10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = trim($_GET['search'] ?? '');
        $lang = $_SESSION['lang'] ?? 'fa';
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
        $services = Service::query()
            ->select([
                'service_table.id',
                'service_table.service_key',
                ($lang === 'fa' ? 'service_table.fa_title ' : 'service_table.en_title') . ' AS title',
                'service_table.parent_id',
                'service_table.created_at',
                'service_table.updated_at',
                ($lang === 'fa' ? 'p.fa_title ' : 'p.en_title') . ' AS parent_title'
            ])
            ->join('service_table AS p', 'service_table.parent_id', '=', 'p.id', 'LEFT')
            ->where('service_table.deleted', '=', 0);

        $column = $lang == "fa" ? "service_table.fa_title" : 'en_title';
        if ($search !== '') {
            $services->whereLike($column, $search);
        }
        if (!empty($sortBy)) {
            if ($sortBy == 'title') {
                $services->orderBy($sortBy, $sortOrder);
            } else if ($sortBy == 'category') {
                $services->orderBy('parent_title', $sortOrder);
            }
        }

        $pagination = $services->paginate($page, $perPage);
        $this->view('admin/services/dashboard', [
            'title' => __('manage_services'),
            'services' => $pagination['data'],
            'pagination' => $pagination,
            'per_page' => $perPage,
            'allowedPerPage' => $allowedPerPage,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'sortTitleUrl' => $sortTitleUrl,
            'sortCategoryUrl' => $sortCategoryUrl,
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
                    redirect("/admin/services/management");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        } else {
            clear_old_input();
        }
        $this->view('admin/services/addCategory', [
            'errors' => $errors,
            'title' => __('services')
        ]);
    }

    public function editCategory($id)
    {
        $errors = [];
        $service = Service::find((int)$id);
        $allService = Service::all();
//        $categories = Service::query()->where("id", "=", $id)->first();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = $_POST['category'] ?? '';
            $addCategory = isset($_POST['serviceCategory']) && $_POST['serviceCategory'] == 'on' ? 1 : 0;
            $fa_title = trim($_POST['fa_title'] ?? '');
            $en_title = trim($_POST['en_title'] ?? '');
            $validator = new Validator($_POST, [
                'fa_title' => 'required|min:2|max:40',
                'en_title' => 'required|min:2|max:40',
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }

            if (empty($errors)) {
                $service->fa_title = $fa_title;
                $service->en_title = $en_title;
                $service->parent_id = ($addCategory == 1) ? 0 : $categoryId;
                $service->service_key = "";
                if ($service->save()) {
                    $_SESSION['flash_success'] = __('category_update');
                    redirect("/admin/services/create");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }
        } else {
            clear_old_input();
        }

        $this->view('admin/services/editCategory', [
            'title' => __('edit_user'),
            'services' => $service,
            'allServices' => $allService,
            'errors' => $errors,


        ]);

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
            redirect("/admin/services/management");
            exit;
        }
        $stmt = Service::query()->where("parent_id", "=", $category->id)->get();
        if (sizeof($stmt) > 0) {
            $_SESSION['flash_error'] = sprintf(__('delete_category_not_allowed'), sizeof($stmt));;
            redirect("/admin/services/management");
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

        redirect("/admin/services/management");
        exit;
    }

    public function getService($serviceId)
    {
        $service = Service::query()
            ->where('id', '=', $serviceId)
            ->first();

        if (!$service) {
            http_response_code(403);
            echo json_encode(['error' => 'invalid service']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode($service->toArray());
        exit;
    }
    public function updateService($id)
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

    public function addService()
    {
        $sortBy = isset($_GET['sortby']) ? $_GET['sortby'] : '';
        $filter = trim($_GET['filter'] ?? 'all');
        $sortOrder = isset($_GET['sortorder']) ? $_GET['sortorder'] : '';
        $sortTitleUrl = (BASE_URL . '/admin/services/create?sortby=title&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');
        $sortCategoryUrl = (BASE_URL . '/admin/services/create?sortby=category&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');
        $sortServiceCountUrl = (BASE_URL . '/admin/services/create?sortby=count&') . (($sortOrder == 'desc' || $sortOrder == '') ? 'sortorder=asc' : 'sortorder=desc');
        $allowedPerPage = [1, 10, 20, 50, 100];
        $perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $allowedPerPage) ? (int)$_GET['per_page'] : 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $categories = Service::query()->where("parent_id", "=", 0)->where("deleted", "=", 0)->get();
        $lang = $_SESSION['lang'] ?? 'fa';
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
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fa_title = $_POST['fa_title'];
            $en_title = $_POST['en_title'];
            $categoryId = $_POST['category'] ?? '';
            $addCategory = isset($_POST['serviceCategory']) && $_POST['serviceCategory'] == 'on' ? 1 : 0;
            $service = new Validator($_POST, [
//                'service_key' => "required|min:2|max:40",
                'fa_title' => "required|min:2|max:40",
                'en_title' => "required|min:2|max:40",
//                'parent_id' => "required|not:0",
            ]);
            if ($service->fails()) {
                $errors = array_merge($errors, $service->errors());
                save_old_input();
            }
            $category = new Service([
                'service_key' => "",
                'fa_title' => $fa_title,
                'en_title' => $en_title,
                'parent_id' => ($addCategory == 1) ? 0 : $categoryId,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted' => 0,
            ]);
            if (empty($errors)) {
                if ($category->save()) {
                    clear_old_input();
                    $_SESSION['flash_success'] = __('add_service_message');
                    redirect("/admin/services/create");
                    exit;
                } else {
                    $errors[] = __('user_save_error');
                }
            }
        } else {
            clear_old_input();
        }
        $column = $lang == "fa" ? "service_table.fa_title" : 'service_table.en_title';
        $services2 = Service::getAll();
        $categoriesData = Service::getCategoriesOnly();
        $servicesData = Service::getServicesOnly();
        $sortByColumn = "service_table.parent_id";
        if (!empty($sortBy)) {
            if ($sortBy == 'title') {
                $sortByColumn = $sortBy;
            } else if ($sortBy == 'category') {
                $sortByColumn = 'parent_title';
            } else if ($sortBy == 'count') {
                $sortByColumn = 'childCount';
            }
        }
        $services2->orderBy($sortByColumn, $sortOrder);

        $allSearchData = Service::getAll();
        $service1 = Service::getAll();
        $service2 = Service::getCategoriesOnly();
        $service3 = Service::getServicesOnly();
        if ($search !== '') {
            $allSearchData->whereLike($column, $search);
            $service1->whereLike($column, $search);
            $service2->whereLike($column, $search);
            $service3->whereLike($column, $search);
            $services2->whereLike($column, $search);
            $categoriesData->whereLike($column, $search);
            $servicesData->whereLike($column, $search);
        }
        if ($filter == "all" || empty($filter)) {
            $pagination = $services2->paginate($page, $perPage);
        } else if ($filter == "categories") {
            $pagination = $categoriesData->paginate($page, $perPage);
        } else if ($filter == "services") {
            $pagination = $servicesData->paginate($page, $perPage);;
        }

        $searchSize = sizeof($allSearchData->get());
        $size1 = sizeof($service1->get());
        $size2 = sizeof($service2->get());
        $size3 = sizeof($service3->get());
        $totalPages = ceil($pagination['total'] / $perPage);
        $this->view('admin/services/addService', [
            'renderPagination' => renderPagination($totalPages, $page, $perPage, $search, $sortBy, $sortOrder, $filter ,$lang),
            'errors' => $errors,
            'title' => __('add_services'),
            "services" => $categories,
            "lang" => $lang,
            "filter" => $filter,
            "allServices" => $pagination['data'],
            "all" => $size1,
            "categorySize" => $size2,
            "serviceSize" => $size3,
            'pagination' => $pagination,
            'per_page' => $perPage,
            'allowedPerPage' => $allowedPerPage,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'sortTitleUrl' => $sortTitleUrl,
            'sortCategoryUrl' => $sortCategoryUrl,
            'sortServiceCountUrl' => $sortServiceCountUrl,
            'items' => $searchSize,
            'page' => $totalPages,
            'first_name' => !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : "",
            'last_name' => !empty($_SESSION['last_name']) ? $_SESSION['last_name'] : "",
        ]);

    }

    public function editService($id)
    {
        $service = Service::find((int)$id);
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fa_title = trim($_POST['fa_title'] ?? '');
            $en_title = trim($_POST['en_title'] ?? '');
            $service_key = trim($_POST['service_key'] ?? '');
            $parent_id = trim($_POST['parent_id'] ?? '');
            $validator = new Validator($_POST, [
                'fa_title' => 'required|min:2|max:40',
                'en_title' => 'required|min:2|max:40',
                'service_key' => 'required|min:2|max:40',
                'parent_id' => 'required|min:1',
            ]);

            if ($validator->fails()) {
                $errors = array_merge($errors, $validator->errors());
                save_old_input();
            }
            if (empty($errors)) {
                $service->fa_title = $fa_title;
                $service->en_title = $en_title;
                $service->service_key = $service_key;
                $service->parent_id = $parent_id;
                if ($service->save()) {
                    $_SESSION['flash_success'] = __('service_update');
                    redirect("/admin/services/management");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }
        } else {
            clear_old_input();
        }

        $allCategory = Service::query()
            ->where("parent_id", "=", 0)
            ->get();

        $this->view('admin/services/editService', [
            'title' => __('edit_user'),
            'categories' => $allCategory,
            'servicess' => $service,
            'errors' => $errors,
        ]);

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
            redirect("/admin/services/create");
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

        redirect("/admin/services/create");
        exit;
    }
}