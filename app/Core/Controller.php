<?php
namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        include BASE_PATH . '/app/Views/layout/header.php';

        $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View '$viewFile' یافت نشد.";
        }

        include BASE_PATH . '/app/Views/layout/footer.php';
    }

}
