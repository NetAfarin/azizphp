<?php
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

use App\Models\User;

$user = User::find(92);

if ($user) {
    echo "<h3>نام: " . $user->first_name . "</h3>";
    echo "<p>شماره موبایل: " . $user->phone_number . "</p>";
    echo "<p>وضعیت فعال: " . ($user->is_active ? 'بله' : 'خیر') . "</p>";
} else {
    echo "کاربری با این ID پیدا نشد.";
}