<?php
$menuItems = [
    [
        'icon' => 'fa-dashboard',
        'title' => __('dashboard'),
        'link' => '/fw/operator/dashboard',
    ],
    [
        'icon' => 'fa-user',
        'title' => __('manage_users'),
        'submenu' => [
            ['title' => __('add'), 'link' => '/fw/admin/user/add'],
            ['title' => __('manage_users'), 'link' => '/fw/admin/user/manage'],
            ['title' => __('settings'), 'link' => '#'],
        ]
    ],
    [
        'icon' => 'fa-user',
        'title' => __('manage_services'),
        'submenu' => [
            ['title' => __('add'), 'link' => '/fw/admin/services/create'],
            ['title' => __('manage_services'), 'link' => '#'],
            ['title' => __('settings'), 'link' => '#'],
        ]
    ],
    [
        'icon' => 'fa-book',
        'title' => __('reserve_list'),
        'submenu' => [
            ['title' => __('add'), 'link' => '/fw/admin/bookings/create'],
            ['title' => __('manage_reserve'), 'link' => '/fw/user/reserve'],
            ['title' => __('settings'), 'link' => '#'],
        ]
    ],
    [
        'icon' => 'fa-gear',
        'title' => __('settings'),
        'link' => '#'
    ],
    [
        'icon' => 'fa-solid fa-compress',
        'title' => __('collapse_menu'),
        'link' => '#'
    ],
];$currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<?php $lang = $_SESSION['lang'] ?? 'fa'; ?>
<body>
<div class="container-fluid" style="padding: 30px 20px 30px 20px">
    <div class="d-lg-none">
        <button class="btn btn-primary icon-hamburger" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
                aria-controls="sidebar">
            <i class="fa fa-bars"></i>
        </button>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-xl-3 col-xxl-2 px-4 d-none d-lg-block">
            <div class="bg-white flex-column  pb-4 sidebar-full-height menu-border-radius">
                <div class="sidebar-scroll d-flex flex-column justify-content-between h-100">

                <img src="<?= asset('img/delarose-black.png') ?>" style="height: 200px; object-fit: contain"
                     class="mt-4">
                <nav class="topnav navbar navbar-light">
                    <ul class="navbar-nav flex-fill w-100 mb-2 menu-drop-down" style="margin:0;">
                        <?php foreach($menuItems as $item): ?>
                            <?php if(isset($item['submenu'])): ?>
                                <?php
                                $isParentActive = false;
                                $isSubActive = false;
                                if (strpos($currentUrl, '/fw/admin/user/edit') === 0) {
                                    if ($item['title'] === __('manage_users')) {
                                        $isParentActive = true;
                                        $isSubActive = true;
                                    }
                                } else {
                                    foreach ($item['submenu'] as $sub) {
                                        if ($sub['link'] === $currentUrl) {
                                            $isParentActive = true;
                                            $isSubActive = true;
                                            break;
                                        }
                                    }
                                    if (!$isParentActive && isset($item['link']) && $item['link'] !== '#') {
                                        if (strpos($currentUrl, $item['link']) === 0) {
                                            $isParentActive = true;
                                        }
                                    }
                                }

                                $parentClass = $isParentActive ? 'active-menu' : '';
                                $collapseShow = $isParentActive ? 'show' : '';
                                $ariaExpanded = $isParentActive ? 'true' : 'false';
                                ?>
                                <li class="nav-item dropdown menu-drop-down <?= $parentClass ?>">
                                    <a href="#<?= str_replace(' ', '', $item['title']) ?>"
                                       data-bs-toggle="collapse"
                                       aria-expanded="<?= $isSubActive ? 'true' : 'false' ?>"
                                       class="justify-content d-flex dropdown-toggle nav-link <?= $parentClass ?>">
                                        <i class="fa <?= $item['icon'] ?> icon-color"></i>
                                        <span class="mx-2 item-text"><?= $item['title'] ?></span>
                                    </a>
                                    <ul class="collapse list-unstyled  w-100 <?= $isSubActive ? 'show' : '' ?>" id="<?= str_replace(' ', '', $item['title']) ?>">
                                        <?php foreach($item['submenu'] as $sub): ?>

                                            <?php
                                            $isEditPage = strpos($currentUrl, '/fw/admin/user/edit') === 0;
                                            $activeClass = ($sub['link'] === $currentUrl) ? 'text-primary' : '';
                                            if ($isEditPage && $sub['link'] === '/fw/admin/user/manage') {
                                                $activeClass = 'text-primary';
                                            }
                                            ?>

                                            <li class="nav-item">
                                                <a class="nav-link <?= $activeClass ?>" href="<?= $sub['link'] ?>">
                                                    <span class="mx-2 under"><?= $sub['title'] ?></span>
                                                </a>
                                            </li>

                                        <?php endforeach; ?>

                                    </ul>
                                </li>
                            <?php else: ?>
                                <?php $activeClass = ($item['link'] === $currentUrl) ? 'text-primary active-menu' : ''; ?>
                                <li class="nav-item justify-content d-flex py-2 normal-menu-item <?= $activeClass ?>">
                                    <a href="<?= $item['link'] ?>" class="d-flex align-items-center text-decoration-none ">
                                        <i class="fa <?= $item['icon'] ?> icon-color"></i>
                                        <span class="item-text mx-2"><?= $item['title'] ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </ul>
                </nav>
                <a href="/fw/user/logout" class="justify-content d-flex nav-item py-2 normal-menu-item text-decoration-none">
                    <i class="fa-solid fa-right-from-bracket icon-color"></i>
                    <span class="item-text"><?= __("logout") ?></span>
                </a>
            </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-xl-9 col-xxl-10 px-3">
            <div class=" mt-lg-0 mt-3">
                <div class="col-12 d-flex  justify-content-between align-items-center">
                    <h4 class="<?php echo $lang == 'en' ? 'title-left-border' : 'title-right-border text-dark' ?> px-2"><?= $title ?></h4>
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
                        <button id="darkModeToggle" class="btn btn-primary py-3">
                        </button>
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
                            <ul class="navbar-nav flex-fill w-100 mb-2 menu-drop-down" style="margin:0;">
                                <?php foreach($menuItems as $item): ?>
                                    <?php if(isset($item['submenu'])): ?>
                                        <?php
                                        $isParentActive = false;
                                        $isSubActive = false;
                                        if (strpos($currentUrl, '/fw/admin/user/edit') === 0) {
                                            if ($item['title'] === __('manage_users')) {
                                                $isParentActive = true;
                                                $isSubActive = true;
                                            }
                                        } else {
                                            foreach ($item['submenu'] as $sub) {
                                                if ($sub['link'] === $currentUrl) {
                                                    $isParentActive = true;
                                                    $isSubActive = true;
                                                    break;
                                                }
                                            }
                                            if (!$isParentActive && isset($item['link']) && $item['link'] !== '#') {
                                                if (strpos($currentUrl, $item['link']) === 0) {
                                                    $isParentActive = true;
                                                }
                                            }
                                        }

                                        $parentClass = $isParentActive ? 'active-menu' : '';
                                        $collapseShow = $isParentActive ? 'show' : '';
                                        $ariaExpanded = $isParentActive ? 'true' : 'false';
                                        ?>
                                        <li class="nav-item dropdown menu-drop-down <?= $parentClass ?>">
                                            <a href="#<?= str_replace(' ', '', $item['title']) ?>"
                                               data-bs-toggle="collapse"
                                               aria-expanded="<?= $isSubActive ? 'true' : 'false' ?>"
                                               class="justify-content d-flex dropdown-toggle nav-link <?= $parentClass ?>">
                                                <i class="fa <?= $item['icon'] ?> icon-color"></i>
                                                <span class="mx-2 item-text"><?= $item['title'] ?></span>
                                            </a>
                                            <ul class="collapse list-unstyled  w-100 <?= $isSubActive ? 'show' : '' ?>" id="<?= str_replace(' ', '', $item['title']) ?>">
                                                <?php foreach($item['submenu'] as $sub): ?>

                                                    <?php
                                                    $isEditPage = strpos($currentUrl, '/fw/admin/user/edit') === 0;
                                                    $activeClass = ($sub['link'] === $currentUrl) ? 'text-primary' : '';
                                                    if ($isEditPage && $sub['link'] === '/fw/admin/user/manage') {
                                                        $activeClass = 'text-primary';
                                                    }
                                                    ?>

                                                    <li class="nav-item">
                                                        <a class="nav-link <?= $activeClass ?>" href="<?= $sub['link'] ?>">
                                                            <span class="mx-2 under"><?= $sub['title'] ?></span>
                                                        </a>
                                                    </li>

                                                <?php endforeach; ?>

                                            </ul>
                                        </li>
                                    <?php else: ?>
                                        <?php $activeClass = ($item['link'] === $currentUrl) ? 'text-primary active-menu' : ''; ?>
                                        <li class="nav-item justify-content d-flex py-2 normal-menu-item <?= $activeClass ?>">
                                            <a href="<?= $item['link'] ?>" class="d-flex align-items-center text-decoration-none ">
                                                <i class="fa <?= $item['icon'] ?> icon-color"></i>
                                                <span class="item-text mx-2"><?= $item['title'] ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            </ul>
                        </nav>
                        <div class="justify-content d-flex nav-item py-2 normal-menu-item">
                            <i class="fa-solid fa-right-from-bracket icon-color"></i>
                            <span class="item-text"><?= __("logout") ?></span>
                        </div>
                    </div>
                </div>
