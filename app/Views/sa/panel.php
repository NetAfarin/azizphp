
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-2 col-lg-2 d-md-block sidebar py-3">
            <h4 class="px-3 mb-4">مدیریت</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="<?= BASE_URL ?>/sa/panel"><?= __('dashboard') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL?>/sa/users"><?= __('manage_users') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/sa/users"><?= __('manage_salons') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/sa/ticket"><?= __('tickets') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/sa/settings"><?= __('settings') ?></a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="<?= BASE_URL ?>/user/logout"><?= __('logout') ?></a></li>

            </ul>
        </nav>

        <!-- Main content -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h2 class="mb-4"><?= $title ?? 'پنل مدیریت' ?></h2>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card card-stats text-bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">تعداد کاربران</h5>
                            <p class="card-text fs-4"><?= $userCount ?? 0 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats text-bg-success">
                        <div class="card-body">
                            <h5 class="card-title">سرویس‌ها</h5>
                            <p class="card-text fs-4"><?= $serviceCount ?? 0 ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-stats text-bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">سفارشات</h5>
                            <p class="card-text fs-4"><?= $orderCount ?? 0 ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    فعالیت‌های اخیر
                </div>
                <div class="card-body">
                    <ul>
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach ($recentActivities as $activity): ?>
                                <li><?= htmlspecialchars($activity) ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>فعلاً فعالیتی ثبت نشده است.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>
