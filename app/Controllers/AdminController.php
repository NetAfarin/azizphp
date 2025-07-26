<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Middlewares\Role;
class AdminController extends Controller
{
    public function panel()
    {
        Role::allow(['admin', 'operator']);
        $this->view('admin/panel', ['title' => __('admin_panel')]);
    }

    public function usersList()
    {
        Role::allow(['admin', 'operator']);

        $users = User::where('deleted', 0);

        $this->view('admin/users', [
            'title' => __('users_list'),
            'users' => $users
        ]);
    }

    public function editUser($id)
    {
        Role::allow(['admin', 'operator']);

        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }

        $this->view('admin/edit_user', [
            'title' => __('edit_user'),
            'user' => $user
        ]);
    }

    public function updateUser($id)
    {
        Role::allow(['admin', 'operator']);

        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (strlen($first_name) < 2) $errors[] = __('first_name_short');

            if (empty($errors)) {
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->is_active = $is_active;

                if ($user->save()) {
                    $_SESSION['flash_success'] = __('user_updated');
                    header("Location: " . BASE_URL . "/admin/users");
                    exit;
                } else {
                    $errors[] = __('save_error');
                }
            }

            $this->view('admin/edit_user', [
                'title' => __('edit_user'),
                'user' => $user,
                'errors' => $errors
            ]);
        }
    }

    public function deleteUser($id)
    {
        Role::allow(['admin']);

        $user = User::find((int)$id);

        if (!$user) {
            $_SESSION['flash_error'] = __('user_not_found');
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }

        $user->deleted = 1;

        if ($user->save()) {
            $_SESSION['flash_success'] = __('user_deleted');
        } else {
            $_SESSION['flash_error'] = __('delete_failed');
        }

        header("Location: " . BASE_URL . "/admin/users");
        exit;
    }
}
