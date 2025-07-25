<!--<h2 class="mb-4">فرم ثبت ‌نام</h2>-->
<h1><?= __('register') ?></h1>

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
        <?= __('register') ?> <?= __('success') ?> ✅
    </div>
<?php endif; ?>

<form method="post" class="row g-3">
    <div class="mb-3">
        <label for="first_name"><?= __('first_name') ?></label>
        <input type="text" class="form-control" name="first_name" id="first_name">
    </div>

    <div class="mb-3">
        <label for="last_name"><?= __('last_name') ?></label>
        <input type="text" class="form-control" name="last_name" id="last_name">
    </div>
    <div class="mb-3">
        <label for="phone_number"><?= __('phone_number') ?></label>
        <input type="text" class="form-control" name="phone_number" id="phone_number">
    </div>

    <div class="mb-3">
        <label for="password"><?= __('password') ?></label>
        <input type="password" class="form-control" name="password" id="password">
    </div>
    <button type="submit" class="btn btn-primary"><?= __('submit') ?></button>
</form>




