<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Middlewares\Auth;

class HomeController extends Controller
{
    public function index(): void
    {
        Auth::handle();

        $this->view('home/index', [
            'title' => __('dashboard'),
            'message' => 'ساختار پایه به‌درستی کار می‌کند!'
        ]);
    }
}