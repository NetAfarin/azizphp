<?php

function flash(string $type = 'success'): ?string
{
    $key = 'flash_' . $type;

    if (!empty($_SESSION[$key])) {
        $message = $_SESSION[$key];
        unset($_SESSION[$key]);
        return "<div class='alert alert-{$type}'>{$message}</div>";
    }

    return null;
}