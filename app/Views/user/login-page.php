<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چیدمان عمودی دو ستون</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?= asset('css/theme.css')?>" rel="stylesheet" />
</head>
<body>
<div class="container-custom position-relative">
    <div class="row h-50 m-0">
        <div class="col-12 p-0">
            <div class="col-full p-0">
                <div class="image-container">
                    <img src="<?= asset("img/bg-login.jpeg") ?>"  />
                </div>
            </div>
        </div>
    </div>

    <div class="row h-50 m-0">
        <div class="col-12 p-0">
            <div class="col-full bg-primary p-0">
            </div>
        </div>
    </div>
<!--<div class="logo-overlay">-->
<!--    <img src="--><?php //= asset('img/a.jpeg') ?><!--" class="logo-img">-->
<!--</div>-->
    <div class="overlay-element shadow-md p-5">
        <form class="w-100">
            <div class="mb-4 mx-2">
                <i class="fa-solid fa-phone-flip icon-custom"></i>
                <label for="exampleInputTel" class="form-label text-black fw-bold pe-2">شماره همراه</label>
                <input type="tel" class="form-control mt-3 ltr-input fw-bold" id="exampleInputTel">
            </div>
            <div class="text-center mt-4">
                <button type="button" class="btn btn-primary px-5">
                    ادامه
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>