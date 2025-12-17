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

    // اگر مقدار null بود، رشته خالی برگردون
    $line = $translations[$key] ?? '';

    // اگر هنوز null یا غیررشته بود، بازم رشته خالی بشه
    if ($line === null) {
        $line = '';
    }

    foreach ($replace as $k => $v) {
        $line = str_replace(":$k", (string)($v ?? ''), $line);
    }

    // در نهایت مطمئن شو همیشه رشته برمی‌گرده
    return (string)$line;
}
