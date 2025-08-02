<?= flash('success') ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label"><?= __('first_name') ?></label>
        <input type="text" name="first_name" class="form-control"
               value="<?= htmlspecialchars($user->first_name ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('last_name') ?></label>
        <input type="text" name="last_name" class="form-control"
               value="<?= htmlspecialchars($user->last_name ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('phone_number') ?></label>
        <input type="text" name="phone_number" class="form-control"
               value="<?= htmlspecialchars($user->phone_number ?? '') ?>">
    </div>
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
            <?= ( $user->is_active == 1) ? 'checked' : '' ?>>
        <label class="form-check-label" for="is_active">
            <?= __('is_active') ?>
        </label>
    </div>
    <button class="btn btn-primary"><?= __('save_changes') ?></button>
</form>
