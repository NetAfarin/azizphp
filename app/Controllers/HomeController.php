<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title' => 'به فریمورک خودت خوش اومدی 🎉',
            'message' => 'ساختار پایه به‌درستی کار می‌کند!'
        ]);
    }
}