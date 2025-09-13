<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL."/".SALON_ID ?>/admin/panel" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
<div class="container-fluid">
    <div class="row">


        <main class=" px-md-4 py-4">
            <h2 class="mb-4"><?= $title ?? 'پنل مدیریت' ?></h2>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                  <a href="<?= BASE_URL."/".SALON_ID ?>/admin/services/categories" class="text-decoration-none">
                      <div class="card card-stats text-bg-primary">
                          <div class="card-body">
                              <h5 class="card-title"><?= __('categories') ?></h5>
                              <p class="card-text fs-4"><?= $categoryCount ?></p>
                          </div>
                      </div>
                  </a>
                </div>
                <div class="col-md-3">
                    <a href="<?= BASE_URL."/".SALON_ID ?>/admin/services" class="text-decoration-none">
                    <div class="card card-stats text-bg-success">
                        <div class="card-body">
                            <h5 class="card-title"><?= __('services') ?></h5>
                            <p class="card-text fs-4"><?= $services  ?></p>
                        </div>
                    </div>
                    </a>
                </div>
            </div>

        </main>
    </div>
</div>

</body>
</html>
