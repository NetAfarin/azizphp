<?php include BASE_PATH . '/app/Views/layout/header.php'; ?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>نمایش کاربر</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h1 class="mb-4 text-success">اطلاعات کاربر</h1>

<ul class="list-group">
    <li class="list-group-item"><strong>نام:</strong> <?= $user->first_name ?></li>
    <li class="list-group-item"><strong>نام خانوادگی:</strong> <?= $user->last_name ?></li>
    <li class="list-group-item"><strong>تاریخ تولد:</strong> <?= $user->birth_date ?></li>
    <li class="list-group-item"><strong>موبایل:</strong> <?= $user->phone_number ?></li>
    <li class="list-group-item"><strong>وضعیت فعال:</strong> <?= $user->is_active ? 'بله' : 'خیر' ?></li>
</ul>

</body>
</html>