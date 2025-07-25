<?php
function __($key, array $replace = []): string
{
    static $translations;

    if (!$translations) {
        $file = BASE_PATH . '/lang/' . APP_LANG . '.php';
        if (file_exists($file)) {
            $translations = require $file;
        } else {
            $translations = [];
        }
    }

    $line = $translations[$key] ?? $key;

    foreach ($replace as $k => $v) {
        $line = str_replace(":$k", $v, $line);
    }

    return $line;
}