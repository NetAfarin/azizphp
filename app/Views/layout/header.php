<?php

use App\Models\UserType;

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
    <link href="<?= asset($dir === 'rtl' ?"css/bootstrap5.3.8.rtl.min.css":"css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

</head>
<body class="container mt-4">

<nav class="navbar navbar-light bg-light mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="navbar-text">
            <?= __('hello_user', ['name' => htmlspecialchars($_SESSION['user_name'] ?? __('guest'))]) ?>
        </span>

        <div class="d-flex align-items-center gap-2">
            <a href="?lang=fa" class="btn btn-sm btn-outline-primary">🇮🇷 فارسی</a>
            <a href="?lang=en" class="btn btn-sm btn-outline-secondary">🇺🇸 English</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL . "/" . SALON_ID ?>/user/profile"
                   class="btn btn-sm btn-outline-primary">👤 <?= __('profile') ?></a>
                <a href="<?= BASE_URL . "/" . SALON_ID ?>/user/logout"
                   class="btn btn-sm btn-danger">🚪 <?= __('logout') ?></a>
                <?php if (isset($_SESSION['user_role']) &&
                    ($_SESSION['user_role'] == UserType::ADMIN ||
                        $_SESSION['user_role'] == UserType::OPERATOR ||
                        $_SESSION['user_role'] == UserType::SUPER_ADMIN ||
                        $_SESSION['user_role'] == UserType::SUPPORT)): ?>
                    <a href="<?= BASE_URL . "/" . SALON_ID ?>/user/register"
                       class="btn btn-sm btn-outline-primary">📝 <?= __('register') ?></a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= BASE_URL . "/" . SALON_ID ?>/user/login"
                   class="btn btn-sm btn-outline-success">🔐 <?= __('login') ?></a>

            <?php endif; ?>
        </div>
    </div>
</nav>
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>
<script> const BASE_URL = "<?= BASE_URL?>";</script>