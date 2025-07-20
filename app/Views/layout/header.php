<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once BASE_PATH . '/app/Helpers/lang.php';
require_once BASE_PATH . '/app/Helpers/flash.php';

$userName = $_SESSION['user_name'] ?? 'مهمان';
$title = $title ?? 'بدون عنوان';

$lang = APP_LANG;
$dir = APP_DIRECTION;
$bootstrapCss = $dir === 'rtl'
    ? "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css"
    : "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css";
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="<?= $bootstrapCss ?>" rel="stylesheet">
</head>
<body class="container mt-4">

<!--<div class="text-end mb-2">-->
<!--    <a href="?lang=fa" class="btn btn-sm btn-outline-primary">🇮🇷 فارسی</a>-->
<!--    <a href="?lang=en" class="btn btn-sm btn-outline-secondary">🇺🇸 English</a>-->
<!--</div>-->

<nav class="navbar navbar-light bg-light mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="navbar-text">
            <?= __('hello_user', ['name' => htmlspecialchars($userName)]) ?>
        </span>

        <div class="d-flex align-items-center gap-2">
            <a href="?lang=fa" class="btn btn-sm btn-outline-primary">🇮🇷 فارسی</a>
            <a href="?lang=en" class="btn btn-sm btn-outline-secondary">🇺🇸 English</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/user/logout" class="btn btn-sm btn-danger">🚪 <?= __('logout') ?></a>
            <?php endif; ?>
        </div>
    </div>
</nav>