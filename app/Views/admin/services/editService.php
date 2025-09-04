<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/admin/services" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
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
<form method="post" action="">
    <?= csrf_field() ?>


    <div class="mb-3">
        <label class="form-label"><?= __('service_title_fa') ?></label>
        <input type="text" name="fa_title" class="form-control"
               value="<?= htmlspecialchars(old('fa_title', $service->fa_title ?? '')) ?>">
        <?php if (!empty($errors['fa_title'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['fa_title'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('service_title_en') ?></label>
        <input type="text" name="en_title" class="form-control"
               value="<?= htmlspecialchars(old('en_title', $service->en_title ?? '')) ?>">
        <?php if (!empty($errors['en_title'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['en_title'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('category_title') ?></label>
        <select class="js-example-basic-single form-select w-100" name="parent_id">
            <option value="0"><?= __('select_category') ?></option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat->id ?>" <?= ($cat->id == $service->parent_id ) && ((old('parent_id', $service->parent_id)) != 0) ? 'selected' : '' ?>>
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
    <a href="<?= BASE_URL ?>/admin/services" class="btn btn-danger"><?= __('cancel') ?></a>
</form>
<script>
    const durationOptions = `
    <?php foreach ($durations as $d): ?>
        <option value="<?= $d->id ?>"><?= htmlspecialchars($d->title) ?></option>
    <?php endforeach; ?>
`;
</script>
<script src="<?= BASE_URL ?>/js/edit-user.js"></script>

