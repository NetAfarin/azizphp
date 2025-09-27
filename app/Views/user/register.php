<h1><?= __('register') ?></h1>

<?php
$publicErrors = array_filter($errors ?? [], fn($k) => is_numeric($k), ARRAY_FILTER_USE_KEY);
if (!empty($publicErrors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $key => $fieldErrors): ?>
                <?php if (is_numeric($key)): ?>
                    <li><?= htmlspecialchars($fieldErrors) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= __('register_success') ?> ✅
    </div>
<?php endif; ?>

<form method="post" class="row g-3" action="<?= BASE_URL ?>/user/register">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="first_name"><?= __('first_name') ?></label>
        <input type="text" class="form-control" name="first_name" id="first_name"
               value="<?= old('first_name') ?>">
        <?php if (!empty($errors['first_name'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['first_name'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="last_name"><?= __('last_name') ?></label>
        <input type="text" class="form-control" name="last_name" id="last_name"
               value="<?= old('last_name') ?>">
        <?php if (!empty($errors['last_name'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['last_name'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="phone_number"><?= __('phone_number') ?></label>
        <input type="text" class="form-control" name="phone_number" id="phone_number"
               value="<?= old('phone_number') ?>">
        <?php if (!empty($errors['phone_number'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['phone_number'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password"><?= __('password') ?></label>
        <input type="password" class="form-control" name="password" id="password">
        <?php if (!empty($errors['password'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['password'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password_confirmation"><?= __('password_confirmation') ?></label>
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
        <?php if (!empty($errors['password_confirmation'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['password_confirmation'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="birth_date"><?= __('birth_date') ?></label>
        <input type="hidden" name="birth_date" id="birth_date" value="<?= old('birth_date') ?>">
        <input type="text" id="birth_date_picker" class="form-control" data-old="<?= old('birth_date') ?>">

        <?php if (!empty($errors['birth_date'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['birth_date'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="captcha"><?= __('captcha') ?></label>
        <div class="d-flex align-items-center">
            <img src="<?= BASE_URL ?>/captcha.php" alt="<?= __('captcha') ?>" class="me-2 border rounded">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="this.previousElementSibling.src='<?= BASE_URL ?>/captcha.php?'+Date.now();">
                <?= __('refresh_captcha') ?>
            </button>
        </div>
        <input type="text" class="form-control mt-2" name="captcha" id="captcha" required>
        <?php if (!empty($errors['captcha'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['captcha'][0]) ?></div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('submit') ?></button>
</form>
<script src="<?= asset('/js/register-user.js') ?>"></script>
