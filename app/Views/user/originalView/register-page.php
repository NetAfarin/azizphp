<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چیدمان عمودی دو ستون</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?= asset('css/theme.css')?>" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
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
    <!--<div class="logo-overlay">-->
    <!--    <img src="--><?php //= asset('img/a.jpeg') ?><!--" class="logo-img">-->
    <!--</div>-->
    <div class="overlay-element shadow-md p-5">
        <form class="w-100">
            <div class="mb-4 mx-2">
                <i class="fa-solid fa-user icon-custom"></i>
                <label for="first_name" class="form-label text-black  pe-2">نام</label>
                <input type="text" class="form-control mt-3  " id="first_name">
            </div> <div class="mb-4 mx-2">
                <i class="fa-solid fa-user icon-custom"></i>
                <label for="last_name" class="form-label text-black  pe-2">نام خانوادگی</label>
                <input type="text" class="form-control mt-3  " id="last_name">
            </div>
            <div class="mb-4 mx-2">
                <div class="input-with-icon">
                        <i class="fa-solid icon-custom fa-lock"></i>
                        <label for="password" class="form-label text-black  pe-2">رمز عبور</label>
                        <input type="password" class="form-control mt-3 ltr-input " id="password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fa-solid icon-custom fa-eye pe-2 "></i>
                        </button>


                </div>
            </div>
            <div class="text-center mt-4">
                <button type="button" class="btn btn-primary px-5">
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