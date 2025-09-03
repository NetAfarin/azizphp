
<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/admin/services" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
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
        <label class="form-label"><?= __('categories_title_fa') ?></label>
        <input type="text" name="fa_title" class="form-control"
               value="<?= htmlspecialchars($service->fa_title ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('categories_title_en') ?></label>
        <input type="text" name="en_title" class="form-control"
               value="<?= htmlspecialchars($service->en_title ?? '') ?>">
    </div>
    <div class="mb-3">
        <select class="js-example-basic-single form-select w-100" name="parent_id">
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat->id ?>" <?= $cat->id == $service->parent_id ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat->fa_title) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['parent_id'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['parent_id'][0]) ?></div>
        <?php endif; ?>
    </div>    <div class="mb-3">
        <label class="form-label"><?= __('service_key') ?></label>
        <input type="text" name="service_key" class="form-control"
               value="<?= htmlspecialchars($service->service_key ?? '') ?>">
    </div>


    <button class="btn btn-primary"><?= __('save_changes') ?></button>
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-danger"><?= __('cancel') ?></a>
</form>
<script>
    const durationOptions = `
    <?php foreach ($durations as $d): ?>
        <option value="<?= $d->id ?>"><?= htmlspecialchars($d->title) ?></option>
    <?php endforeach; ?>
`;
</script>
<script src="<?= BASE_URL ?>/js/edit-user.js"></script>

