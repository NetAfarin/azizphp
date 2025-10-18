<?php $lang = $_SESSION['lang'] ?? 'fa'; ?>
<body style="background: #E9F3F9; padding: 30px 20px 30px 20px">
<div class="container-fluid">
    <div class="row">
        <div class="d-lg-none">
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
                    aria-controls="sidebar">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <div class="col-lg-3 col-md-3 col-xl-3 col-xxl-2 pe-4 d-none d-lg-block">
            <div class="bg-white d-flex flex-column gap-5 pb-4 sidebar-full-height menu-border-radius">
                <img src="<?= asset('img/delarose-black.png') ?>" style="height: 200px; object-fit: contain"
                     class="mt-4">
                <nav class="topnav navbar navbar-light " id="menuBar">
                    <ul class="navbar-nav flex-fill w-100 mb-2 " style="margin: 0 0 0 0">
                        <li class="nav-item justify-content d-flex py-2 normal-menu-item">
                            <i class="fa fa-dashboard icon-color"></i>
                            <span class="item-text mx-2 "><?= __("dashboard") ?></span>
                        </li>
                        <li class="nav-item  dropdown ">
                            <a href="#dashboard" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle  nav-link ">
                                <i class="fa fa-user icon-color"></i>
                                <span class="mx-2 item-text px-2"><?= __("manage_services") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 show " id="dashboard">
                                <li class="nav-item  active">
                                    <a class="nav-link  " href="./index.html"><span
                                                class="mx-2 under text-primary"><?= __("add") ?></span></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  " href="./dashboard-analytics.html"><span
                                                class="mx-2 under "><?= __("manage_services") ?></span></a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link  " href="./dashboard-sales.html"><span
                                                class="mx-2 under "><?= __("settings") ?></span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown ">
                            <a href="#manageService" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle nav-link ">
                                <i class="fa fa-user icon-color"></i>
                                <span class="mx-2 item-text px-2"><?= __("manage_users") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 " id="manageService">
                                <li class="nav-item active">
                                    <a class="nav-link  " href="./index.html"><span
                                                class="mx-2 under text-primary"><?= __("add") ?></span></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  " href="./dashboard-analytics.html"><span
                                                class="mx-2 under"><?= __("manage_users") ?></span></a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link  " href="./dashboard-sales.html"><span
                                                class="mx-2 under "><?= __("settings") ?></span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item  dropdown">
                            <a href="#reserveList" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle nav-link ">
                                <i class="fa fa-book icon-color"></i>
                                <span class="mx-2 item-text px-2">لیست رزروها</span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 " id="reserveList">
                                <li class="nav-item  active">
                                    <a class="nav-link  " href="./index.html"><span class="mx-2 under text-primary">افزودن</span></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  " href="./dashboard-analytics.html"><span class="mx-2 under ">مدیریت رزرو ها</span></a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link  " href="./dashboard-sales.html"><span
                                                class="mx-2 under">تنظیمات</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item justify-content  d-flex  py-2 normal-menu-item ">
                            <i class="fa fa-gear icon-color"></i>
                            <span class="item-text"><?= __("settings") ?></span>
                        </li>
                        <li class="nav-item justify-content d-flex  py-2 normal-menu-item">
                            <i class="fa-solid fa-compress icon-color"></i>
                            <span class="item-text"><?= __("collapse_menu") ?></span>
                        </li>
                    </ul>

                </nav>
                <div class="justify-content d-flex nav-item py-2 normal-menu-item">
                    <i class="fa-solid fa-right-from-bracket icon-color"></i>
                    <span class="item-text"><?= __("logout") ?></span>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-start p-0 rounded-5 m-2" tabindex="-1" id="sidebar"
             aria-labelledby="sidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="sidebarLabel"><?= __("menu") ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <nav class="topnav navbar navbar-light">

                    <ul class="navbar-nav flex-fill w-100 mb-2 " style="margin: 0 0 0 0">
                        <li class="nav-item justify-content d-flex py-2 normal-menu-item">
                            <i class="fa fa-dashboard icon-color"></i>
                            <span class="item-text mx-2 "><?= __("dashboard") ?></span>
                        </li>
                        <li class="nav-item  dropdown ">
                            <a href="#dashboard" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle  nav-link  <?php echo $lang == 'en' ? 'left-border' : 'right-border' ?>">
                                <i class="fa fa-user icon-color"></i>
                                <span class="mx-2 item-text "><?= __("manage_users") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 show " id="dashboard">
                                <li class="nav-item  active">
                                    <a class="nav-link  " href="./index.html"><span
                                                class="mx-2 under text-primary"><?= __("add") ?></span></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  " href="./dashboard-analytics.html"><span
                                                class="mx-2 under "><?= __("manage_users") ?></span></a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link  " href="./dashboard-sales.html"><span
                                                class="mx-2 under"><?= __("settings") ?></span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown ">
                            <a href="#manageService" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle nav-link ">
                                <i class="fa fa-user icon-color"></i>
                                <span class="mx-2 item-text"><?= __("manage_services") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 " id="manageService">
                                <li class="nav-item  active">
                                    <a class="nav-link  " href="./index.html"><span
                                                class="mx-2 under text-primary"><?= __("add") ?></span></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  " href="./dashboard-analytics.html"><span
                                                class="mx-2 under "><?= __("manage_services") ?></span></a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link  " href="./dashboard-sales.html"><span
                                                class="mx-2 under"><?= __("settings") ?></span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item  dropdown">
                            <a href="#reserveList" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle nav-link ">
                                <i class="fa fa-book icon-color"></i>
                                <span class="mx-2 item-text"><?= __("reserve_list") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 " id="reserveList">
                                <li class="nav-item  active">
                                    <a class="nav-link  " href="./index.html"><span
                                                class="mx-2 under text-primary"><?= __("add") ?></span></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  " href="./dashboard-analytics.html"><span
                                                class="mx-2 under "><?= __("manage_reserve") ?></span></a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link  " href="./dashboard-sales.html"><span
                                                class="mx-2 under"><?= __("settings") ?></span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item justify-content  d-flex  py-2 normal-menu-item ">
                            <i class="fa fa-gear icon-color"></i>
                            <span class="item-text mx-2 "><?= __("settings") ?></span>
                        </li>
                        <li class="nav-item justify-content d-flex py-2 normal-menu-item">
                            <i class="fa-solid fa-compress icon-color"></i>
                            <span class="item-text mx-2 "><?= __("collapse_menu") ?></span>
                        </li>
                    </ul>

                </nav>
                <div class="justify-content d-flex nav-item  py-2 normal-menu-item">
                    <a href="<?= BASE_URL ?>/user/logout" class="text-decoration-none">
                        <i class="fa-solid fa-right-from-bracket icon-color"></i>
                        <span class="item-text mx-2 "><?= __("logout") ?></span>
                    </a>

                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-xl-9 col-xxl-10 pe-3">
            <div class="row mt-lg-0 mt-3">
                <div class="col-12 d-flex  justify-content-between align-items-center">
                    <h4 class="<?php echo $lang == 'en' ? 'left-border' : 'right-border' ?> ps-2"><?= $title ?></h4>
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-center ">
                            <div class="profile-details" style="width: 60px; height: 60px">
                                <i class="fa-solid fa-bell icon-color"></i>
                            </div>
                        </div>
                        <div class="dropdown ps-2">
                            <button class="btn btn-white py-3 rounded-4 dropdown-toggle d-flex justify-content"
                                    id="navbarDropdownMenuLink" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <span class="fa fa-user"></span>
                                <span class=" ps-3 pe-3">
                                 <?php echo (empty($first_name) || empty($last_name)) ? "نامشخص" : "$first_name $last_name"; ?>
                             </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right " aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="#"><?= __("profile") ?></a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item" href="<?= BASE_URL ?>/user/logout"><?= __('logout') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5"><h4><?= __("basic_data") ?></h4></div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="username"><?= __("name") ?> <span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="username">
                    </div>
                    <div class="col-6">
                        <label for="lastname"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="lastname">
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="national-code"><?= __("national_code") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control ltr-input" id="national-code">
                    </div>
                    <div class="col-6">
                        <label for="birthday-date"><?= __("birth_date") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="birthday-date">
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="phone-number"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control ltr-input" id="phone-number">
                    </div>
                    <div class="col-6">
                        <label for="user-role"><?= __("role") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="user-role">
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <label for="user-role"><?= __("postal_address") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="user-role">
                    </div>
                </div>
                    <div class="row mt-5">
                        <hr>
                    </div>
                    <div class="row mt-5"><h4><?= __("job_information") ?></h4></div>
                    <div class="row mt-5">
                        <div class="col-6">
                            <label for="break_time" class="form-label"><?= __("break_time") ?></label>
                            <select class="form-select" id="break_time">
                                <option value="1">12 و نیم الی 13 ونیم</option>
                                <option value="1">13 و نیم الی 14 ونیم</option>
                                <option value="1">14 و نیم الی 15 ونیم</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="multiple-select-field" class="form-label"><?= __("skills") ?><span class="bullet-color"> *</span></label>
                            <select class="form-select" id="multiple-select-field" multiple>
                                <option>Christmas Island</option>
                                <option>South Sudan</option>
                                <option>United States</option>
                                <option>Canada</option>
                                <option>Canada3</option>
                                <option>Canada4</option>
                                <option>Canada5</option>
                                <option>Canada6</option>
                                <option>Canada7</option>
                                <option>Canada8</option>
                                <option>Canada9</option>
                                <option>Canada10</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5 ">
                        <span><?= __("shift") ?></span>
                        <div class="table-wrapper mt-3">
                            <div class="table-responsive ">
                                <table class="table custom-table">
                                    <thead class="table-primary ">
                                    <tr>
                                        <th><?= __("day") ?></th>
                                        <th><?= __("start_work") ?></th>
                                        <th><?= __("end_work") ?></th>
                                        <th><?= __("status") ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>شنبه</td>
                                        <td>
                                            <i class="fa-light fa-clock icon-color pe-1"></i>
                                            <span>8:00</span>
                                        </td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        تعطیل
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="flexCheckDefault">
                                                </div>
                                            </div>

                                        </td>

                                    </tr>
                                    <tr>
                                        <td>یکشنبه</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="flexCheckDefault2">
                                                        تعطیل
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="flexCheckDefault2">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>دوشنبه</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="flexCheckDefault3">
                                                        تعطیل
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="flexCheckDefault3">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>سه شنبه</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="flexCheckDefault4">
                                                        تعطیل
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="flexCheckDefault4">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>چهارشنبه</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="flexCheckDefault5">
                                                        تعطیل
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="flexCheckDefault5">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>پنج شنبه</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="flexCheckDefault6">
                                                        تعطیل
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="flexCheckDefault6">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>جمعه</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                        <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="flexCheckDefault7">
                                                        تعطیل
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="flexCheckDefault7">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-5 px-5">افزودن</button>
                    </div>


            </div>
</body>
<script>
    $(document).ready(function () {
        $('#multiple-select-field').select2()
    })
    document.addEventListener('DOMContentLoaded', function () {
        const lang = "<?php echo $_GET['lang'] ?? 'fa'; ?>";
        const dropdownLinks = document.querySelectorAll('.nav-item.dropdown > a.dropdown-toggle[href^="#"]');

        dropdownLinks.forEach(link => {
            const collapseId = link.getAttribute('href');
            const collapseEl = document.querySelector(collapseId);
            if (!collapseEl) return;

            // بررسی اگر از قبل باز است
            if (collapseEl.classList.contains('show')) {
                if (lang == "fa") {
                    link.classList.add('right-border');
                } else {
                    link.classList.add('left-border');
                }
                link.setAttribute('aria-expanded', 'true');
            }

            collapseEl.addEventListener('show.bs.collapse', function () {
                if (lang == "fa") {
                    dropdownLinks.forEach(l => l.classList.remove('right-border'));
                } else {
                    dropdownLinks.forEach(l => l.classList.remove('left-border'));
                }

                // به لینک جاری اضافه کن
                if (lang == "fa") {
                    link.classList.add('right-border');
                } else {
                    link.classList.add('left-border');
                }
                link.setAttribute('aria-expanded', 'true');
            });

            collapseEl.addEventListener('hide.bs.collapse', function () {
                if (lang == "fa") {
                    link.classList.remove('right-border');
                } else {
                    link.classList.remove('left-border');
                }
                link.setAttribute('aria-expanded', 'false');
            });
        });
    });
</script>


