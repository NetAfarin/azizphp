<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL."/".SALON_ID ?>/admin/services/categories" class="btn btn-outline-primary">
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
        <label class="form-label"><?= __('category_title_fa') ?></label>
        <input type="text" name="fa_title" class="form-control"
               value="<?= htmlspecialchars(old('fa_title', $categories->fa_title ?? '')) ?>">
        <?php if (!empty($errors['fa_title'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['fa_title'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('category_title_en') ?></label>
        <input type="text" name="en_title" class="form-control"
               value="<?=  htmlspecialchars(old('en_title', $categories->en_title ?? '')) ?>">
        <?php if (!empty($errors['en_title'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['en_title'][0]) ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('service_key') ?></label>
        <input type="text" name="service_key" class="form-control"
               value="<?=  htmlspecialchars(old('service_key', $categories->service_key ?? '')) ?>">
        <?php if (!empty($errors['service_key'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['service_key'][0]) ?></div>
        <?php endif; ?>
    </div>


    <button class="btn btn-primary"><?= __('save_changes') ?></button>
    <a href="<?= BASE_URL."/".SALON_ID ?>/admin/services/categories" class="btn btn-danger"><?= __('cancel') ?></a>
</form>
<script>
    const durationOptions = `
    <?php foreach ($durations as $d): ?>
        <option value="<?= $d->id ?>"><?= htmlspecialchars($d->title) ?></option>
    <?php endforeach; ?>
`;
</script>
<script src="<?= asset('js/edit-user.js') ?>"></script>

