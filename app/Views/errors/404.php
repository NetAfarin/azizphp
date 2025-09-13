<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>صفحه پیدا نشد</title>
    <link rel="stylesheet" href="<?=asset("/assets/css/bootstrap5.3.8.min.css") ?>">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }
        .error-box {
            max-width: 500px;
        }
    </style>
</head>
<body>
<div class="error-box">
    <h1 class="display-3 text-danger">404</h1>
    <h3>صفحه مورد نظر یافت نشد</h3>
    <p>ممکن است لینک تغییر کرده یا پاک شده باشد.</p>
    <a href="<?= BASE_URL."/".SALON_ID ?>/admin/panel" class="btn btn-primary">بازگشت به داشبورد</a>
    <a href="<?= BASE_URL."/".SALON_ID ?>/" class="btn btn-secondary">بازگشت به صفحه اصلی</a>
</div>
</body>
</html>
