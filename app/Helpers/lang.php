<?php
function __($key) {
    static $translations;

    if (!$translations) {
        $file = BASE_PATH . '/lang/' . APP_LANG . '.php';
        if (file_exists($file)) {
            $translations = require $file;
        } else {
            $translations = [];
        }
    }

    return $translations[$key] ?? $key;
}