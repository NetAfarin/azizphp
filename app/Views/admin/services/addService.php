<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/admin/services/categories" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
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
                                   placeholder="عنوان خود را به فارسی وارد کنید" required
                                   value="<?= isset($_POST['fa_title']) ? htmlspecialchars($_POST['fa_title']) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label for="enTitleInput" class="form-label"><?= __("service_title_en") ?></label>
                            <input type="text" class="form-control" id="enTitleInput" name="en_title"
                                   placeholder="عنوان خود را به انگلیسی وارد کنید" required
                                   value="<?= isset($_POST['en_title']) ? htmlspecialchars($_POST['en_title']) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <select class="js-example-basic-single form-select w-100" name="categoryId" required>
                                <?php foreach ($services as $cat): ?>
                                    <option value="<?php echo $cat->id; ?>">
                                        <?php echo $cat->fa_title; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="serviceKeyInput" class="form-label"><?= __('service_key') ?></label>
                            <input type="text" class="form-control" id="serviceKeyInput" name="service_key"
                                   placeholder="کلید خدمات خود را وارد کنید" required
                                   value="<?= isset($_POST['fa_title']) ? htmlspecialchars($_POST['service_key']) : '' ?>">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">ارسال</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= asset('js/add-service.js')?>"></script>