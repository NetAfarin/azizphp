<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Middlewares\AuthMiddleware;
use App\Models\User;

class HomeController extends Controller
{
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = $_SESSION['user_id'] ?? null;
        $user = null;
        $userType = $_SESSION['user_role'] ?? 'guest';;

        if ($userId) {
            $user = User::find($userId);
        }
        $sections = [];

        if (in_array($userType, ['admin', 'operator'])) {
            $sections['users'] = [
                'title' => __('users_list'),
                'url' =>BASE_URL. '/admin/users'
            ];
        }

        if ($userType === 'customer') {
            $sections['profile'] = [
                'title' =>  __('my_profile'),
                'url' =>BASE_URL. '/user/profile'
            ];
        }

        $sections['dashboard_info'] = [
            'title' => __('dashboard'),
            'description' => __('dashboard_description')
        ];

        $this->view('home/index', [
            'user' => $user,
            'sections' => $sections
        ]);
    }
}