<?php $lang = $_SESSION['lang'] ?? 'fa'; ?>
<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= __('register_success') ?> ✅
    </div>
<?php endif; ?>

<body>
<div class="container-custom position-relative">

    <div class="row h-40 m-0">
        <div class="col-12 p-0">
            <div class="col-full1 p-0">
                <div class="image-container">
                    <img src="<?= asset("img/bg-login.jpeg") ?>"  />
                </div>
            </div>
        </div>
    </div>

    <div class="row h-60 m-0">
        <div class="col-12 p-0">
            <div class="col-full2 bg-primary p-0">
            </div>
        </div>
    </div>
    <div class="overlay-element shadow-md p-5 text-center">
        <form class="w-100" method="post">
            <?= csrf_field() ?>
            <div class="mb-3 mx-2 text-start">
                   <i class="fa-solid fa-phone-flip icon-custom <?php if($lang == 'en') echo 'ltr-icon'; ?>"></i>
                   <label for="exampleInputTel" class="form-label text-black px-1"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
                <input type="tel" name="phone_number" class="form-control mt-0  ltr-input" id="exampleInputTel">
                <?php if (!empty($errors['phone_number'])) : ?>
                    <div class="text-danger small"><?= htmlspecialchars($errors['phone_number'][0]) ?></div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    <?= __("continue") ?>
                </button>
            </div>
        </form>
    </div>

</div>
</body>
</html>