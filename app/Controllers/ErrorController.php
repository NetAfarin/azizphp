<?php

namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller
{
    public function forbidden()
    {
        http_response_code(403);
        $this->view('errors/forbidden', [
            'title' => __('access_denied')
        ]);
    }

    public function notFound()
    {
        http_response_code(404);
        $this->view('errors/404', [
            'title' => __('not_found')
        ]);
    }
}
