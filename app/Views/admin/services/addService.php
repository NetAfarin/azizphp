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
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i><?= __("add_service") ?></h4>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="faTitleInput" class="form-label"><?= __("service_title_fa") ?></label>
                            <input type="text" class="form-control" id="faTitleInput" name="fa_title"
                                   placeholder="<?= __("enter_category_title_placeholder") ?>"
                                   value="<?= htmlspecialchars(old('fa_title')) ?>">
                            <?php if (!empty($errors['fa_title'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['fa_title'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="enTitleInput" class="form-label"><?= __("service_title_en") ?></label>
                            <input type="text" class="form-control" id="enTitleInput" name="en_title"
                                   placeholder="<?= __("enter_category_english_title_placeholder") ?>"
                                   value="<?=  htmlspecialchars(old('en_title'))?>">
                            <?php if (!empty($errors['fa_title'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['en_title'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label"><?= __("category") ?></label>
                            <select class="js-example-basic-single js-states form-control" name="parent_id"  id="parent_id" >
                            <option value="0"><?= __("select_category") ?></option>
                                <?php foreach ($services as $cat): ?>
                                    <option value="<?php echo $cat->id; ?>" <?= ($cat->id == old('parent_id') ) && ((old('parent_id',  0)) != 0) ? 'selected' : '' ?>>
                                        <?= $cat->fa_title ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                            <?php

                            if (!empty($errors['parent_id'])): ?>
                                <div class="text-danger small"><?= htmlspecialchars($errors['parent_id'][0]) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="serviceKeyInput" class="form-label"><?= __('service_key') ?></label>
                            <input type="text" class="form-control" id="serviceKeyInput" name="service_key"
                                   placeholder="<?= __("enter_category_service_key_placeholder") ?>"
                                   value="<?= htmlspecialchars(old('service_key')) ?>">
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
<script src="<?= asset('js/add-service.js')?>"></script>