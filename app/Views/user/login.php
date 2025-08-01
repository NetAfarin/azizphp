<h1><?= __('login') ?></h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= $e ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['flash_success'] ?>
        <?php unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<form method="post" class="row g-3">
    <?= csrf_field() ?>
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
