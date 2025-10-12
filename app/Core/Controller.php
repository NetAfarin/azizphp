<?php
namespace App\Core;

class Controller
{
    use Loggable;

    protected function view(string $view, array $data = []): void
    {
        if($view != "user/login-page"){
            extract($data);
            include BASE_PATH . '/app/Views/layout/header.php';
        }
            $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';
            if (file_exists($viewFile)) {
                require $viewFile;
            } else {
                echo "View '$viewFile' یافت نشد.";
            }
        if($view != "user/login-page") {
            include BASE_PATH . '/app/Views/layout/footer.php';
        }

    }
}
