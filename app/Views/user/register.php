<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>فرم ثبت‌نام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2 class="mb-4">ثبت‌نام کاربر جدید</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= $e ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        کاربر با موفقیت ثبت شد ✅
    </div>
<?php endif; ?>

<form method="post" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">نام</label>
        <input type="text" name="first_name" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">نام خانوادگی</label>
        <input type="text" name="last_name" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">شماره موبایل</label>
        <input type="text" name="phone_number" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">رمز عبور</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">ثبت‌نام</button>
    </div>
</form>

</body>
</html>

