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
            <div class="col-lg-6 col-md-6 col-sm-12 mx-auto">
                <ul class="nav nav-tabs nav-justified mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-password-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#pane-password"
                                type="button" role="tab"
                                aria-controls="pane-password" aria-selected="true">
                            با رمز عبور
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-otp-btn"
                                data-bs-toggle="tab"
                                data-bs-target="#pane-otp"
                                type="button" role="tab"
                                aria-controls="pane-otp" aria-selected="false">
                            با کد تایید
                        </button>
                    </li>
                </ul>
            </div>


            <div class="tab-content">
                <div class="tab-pane fade show active" id="pane-password" role="tabpanel" aria-labelledby="tab-password-btn">
                    <form id="form-password">
                        <div class="mb-4 mx-2">
                            <div class="input-with-icon">
                                <i class="fa-solid icon-custom fa-lock"></i>
                                <label for="password-input" class="form-label text-black pe-2">رمز عبور</label>
                                <input type="password" class="form-control mt-3 ltr-input" id="password-input">
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="fa-solid inside-icon fa-eye pe-2"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary px-5">ادامه</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="pane-otp" role="tabpanel" aria-labelledby="tab-otp-btn">
                    <form id="form-otp">
                        <div class="mb-4 mx-2">
                            <div class="input-with-icon">
                                <i class="fa-solid icon-custom fa-key"></i>
                                <label for="otp-code" class="form-label text-black pe-2">کد تایید</label>
                                <input type="text" class="form-control mt-3 ltr-input" id="otp-code">
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" class="btn btn-primary px-5">ادامه</button>
                        </div>
                    </form>
                </div>
            </div>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fa-solid icon-custom fa-eye pe-2 "  id="togglePassword"></i>' : '<i class="fa-solid icon-custom fa-eye-slash  pe-2 "  id="togglePassword"></i>';
    });
    function openTab(evt, tabId) {
        var tabcontent = document.getElementsByClassName("tabcontent");
        for (var i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        var tablinks = document.getElementsByClassName("tablinks");
        for (var i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        document.getElementById(tabId).style.display = "block";
        evt.currentTarget.classList.add("active");
    }
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const target = document.querySelector(this.getAttribute('data-bs-target'));
                const currentActive = document.querySelector('.tab-pane.active');

                if (currentActive && currentActive !== target) {
                    currentActive.classList.remove('show', 'active');
                    currentActive.classList.add('fading-out');

                    setTimeout(() => {
                        currentActive.classList.remove('fading-out');
                        target.classList.add('show', 'active');
                    }, 500);
                }
            });
        });
    });
</script>
</body>
</html>