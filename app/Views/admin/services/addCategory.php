<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/admin/services/categories" class="btn btn-outline-primary">
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

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i><?= __("creat_category")?></h4>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="faTitleInput" class="form-label"><?= __("category_title")?></label>
                            <input type="text" class="form-control" id="faTitleInput" name="fa_title"
                                   placeholder="<?= __("enter_category_title_placeholder")?>"
                                   value="<?= isset($_POST['fa_title']) ? htmlspecialchars($_POST['fa_title']) : '' ?>">
                            <?php if (!empty($errors['fa_title'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['fa_title'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="enTitleInput" class="form-label"><?= __("category_english_title")?></label>
                            <input type="text" class="form-control" id="enTitleInput" name="en_title"
                                   placeholder="<?= __("enter_category_english_title_placeholder")?>"
                                   value="<?= isset($_POST['en_title']) ? htmlspecialchars($_POST['en_title']) : '' ?>">
                            <?php if (!empty($errors['en_title'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['en_title'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="serviceKeyInput" class="form-label"><?= __('service_key') ?></label>
                            <input type="text" class="form-control" id="serviceKeyInput" name="service_key"
                                   placeholder="<?= __("enter_category_service_key_placeholder")?>"
                                   value="<?= isset($_POST['service_key']) ? htmlspecialchars($_POST['service_key']) : '' ?>">
                            <?php if (!empty($errors['service_key'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['service_key'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><?= __("submit")?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>