<?php $lang = $_SESSION['lang'] ?? 'fa'; ?>
<body style="background: #E9F3F9; padding: 30px 20px 30px 20px">
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xl-3 col-xxl-2 px-4 d-none d-lg-block">
            <div class="bg-white d-flex flex-column gap-5 pb-4 sidebar-full-height menu-border-radius">
                <img src="<?= asset('img/delarose-black.png') ?>" style="height: 200px; object-fit: contain"
                     class="mt-4">
                <nav class="topnav navbar navbar-light " id="menuBar">
                    <ul class="navbar-nav flex-fill w-100 mb-2 " style="margin: 0 0 0 0">
                        <li class="nav-item justify-content d-flex py-2 normal-menu-item">
                            <i class="fa fa-dashboard icon-color"></i>
                            <span class="item-text mx-2 "><?= __("dashboard") ?></span>
                        </li>
                        <li class="nav-item dropdown ">
                            <a href="#manageUsers" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle nav-link ">
                                <i class="fa fa-user icon-color"></i>
                                <span class="mx-2 item-text px-1 "><?= __("manage_users") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 show" id="manageUsers">
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
                        <li class="nav-item  dropdown ">
                            <a href="#manageService" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle  nav-link ">
                                <i class="fa fa-user icon-color"></i>
                                <span class="mx-2 item-text px-1"><?= __("manage_services") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100  " id="manageService">
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
                        <li class="nav-item  dropdown">
                            <a href="#reserveList" data-bs-toggle="collapse" aria-expanded="false"
                               class="justify-content d-flex dropdown-toggle nav-link ">
                                <i class="fa fa-book icon-color"></i>
                                <span class="mx-2 item-text px-1"><?= __("reserves_list") ?></span>
                            </a>
                            <ul class="collapse list-unstyled pl-4 w-100 " id="reserveList">
                                <li class="nav-item  active">
                                    <a class="nav-link  " href="./index.html"><span class="mx-2 under text-primary"><?= __("add") ?></span></a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link  " href="./dashboard-analytics.html"><span class="mx-2 under "><?= __("manage_reserve") ?></span></a>
                                </li>
                                <li class="nav-item m-0">
                                    <a class="nav-link  " href="./dashboard-sales.html"><span
                                                class="mx-2 under"><?= __("setting") ?></span></a>
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
        <div class="col-lg-9 col-md-12 col-xl-9 col-xxl-10 px-3">
            <div class=" mt-lg-0 mt-3">
                <div class="col-12 d-flex  justify-content-between align-items-center">
                    <h4 class="<?php echo $lang == 'en' ? 'title-left-border' : 'title-right-border' ?> px-2"><?= $title ?></h4>
                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex justify-content-center">
                            <div class="profile-details py-3" style="width: 60px;">
                                <i class="fa-solid fa-bell icon-color"></i>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-white py-3 rounded-4 d-flex align-items-center dropdown-toggle"
                                    id="navbarDropdownMenuLink"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <span class="fa fa-user"></span>
                                <span class="px-2 text-black">
                                   <?php echo (empty($first_name) || empty($last_name)) ? "نامشخص" : "$first_name $last_name"; ?>
                               </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item" href="#"><?= __("profile") ?></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/user/logout"><?= __('logout') ?></a></li>
                            </ul>
                        </div>

                    </div>
                </div>