<?php
$publicErrors = array_filter($errors ?? [], fn($k) => is_numeric($k), ARRAY_FILTER_USE_KEY);
if (!empty($publicErrors)): ?>
    <div class="toast align-items-center text-bg-danger border-1 show overlay-element1 mt-5" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <ul class="mb-0">
                    <?php foreach ($errors as $key => $fieldErrors): ?>
                        <?php if (is_numeric($key)): ?>
                        <?= htmlspecialchars($fieldErrors) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= __('register_success') ?> ✅
    </div>
<?php endif; ?>
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
    <!--<div class="logo-overlay">-->
    <!--    <img src="--><?php //= asset('img/a.jpeg') ?><!--" class="logo-img">-->
    <!--</div>-->
    <div class="overlay-element shadow-md p-5">
        <form class="w-100" method="post">
            <?= csrf_field() ?>
            <div class="mb-3 mx-2">
                <i class="fa-solid fa-user icon-custom"></i>
                <label for="first_name" class="form-label text-black  pe-2">نام</label>
                <input type="text" class="form-control mt-2  " id="first_name" name="first_name">
                <?php if (!empty($errors['first_name'])) : ?>
                    <div class="text-danger small"><?= htmlspecialchars($errors['first_name'][0]) ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3 mx-2">
                <i class="fa-solid fa-user icon-custom"></i>
                <label for="last_name" class="form-label text-black  pe-2">نام خانوادگی</label>
                <input type="text" class="form-control mt-2  " id="last_name" name="last_name">
                <?php if (!empty($errors['last_name'])) : ?>
                    <div class="text-danger small"><?= htmlspecialchars($errors['last_name'][0]) ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3 mx-2">
                <div class="input-with-icon">
                        <i class="fa-solid icon-custom fa-lock"></i>
                        <label for="password" class="form-label text-black  pe-2">رمز عبور</label>
                        <input type="password" class="form-control mt-2 ltr-input " id="password" name="password">
                    <?php if (!empty($errors['password'])) : ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['password'][0]) ?></div>
                    <?php endif; ?>
                        <button type="button" class="password-toggle" id="togglePassword" >
                            <i class="fa-solid icon-custom fa-eye pe-2 "></i>
                        </button>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">
                    ادامه
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fa-solid icon-custom fa-eye pe-2 "  id="togglePassword"></i>' : '<i class="fa-solid icon-custom fa-eye-slash  pe-2 "  id="togglePassword"></i>';
    });

</script>
</body>
</html>