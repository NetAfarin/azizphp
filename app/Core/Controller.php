<?php
namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = [])
    {
        extract($data); // تبدیل کلیدهای آرایه به متغیر
        $viewFile = BASE_PATH  . '/app/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View '$view' not found";
        }
    }
}
