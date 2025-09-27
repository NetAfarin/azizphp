<?= flash('success') ?>
<?= flash('error') ?>
<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/admin/services/management" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
<form method="get" class="mb-3 d-flex align-items-center gap-2">
    <label for="per_page" class="form-label mb-0"><?= __('per_page') ?>:</label>
    <select name="per_page" id="per_page" class="form-select w-auto" onchange="this.form.submit()">
        <?php foreach ($allowedPerPage as $opt): ?>
            <option value="<?= $opt ?>" <?= $per_page === $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
    </select>
    <noscript><button type="submit" class="btn btn-primary btn-sm"><?= __('apply') ?></button></noscript>
</form>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><?= __('services') ?></h4>
        <a href="<?= BASE_URL ?>/admin/services/create" class="btn btn-light btn-sm">➕ <?= __('add_service') ?></a>
    </div>
    <div class="card-body">

        <?php if (empty($services)): ?>
            <div class="alert alert-info"><?= __('no_sub_categories_found') ?></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center"><?= __('service_title') ?></th>
                        <th class="text-center"><?= __('service_key') ?></th>
                        <th class="text-center"><?= __('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $startNumber = (($pagination['current_page'] - 1) * $per_page) + 1;
                    $count = $startNumber;
                    ?>
                    <?php foreach ($services as $sub): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($count) ?></td>
                            <td class="text-center"><?= htmlspecialchars($lang === 'fa' ? $sub->fa_title : $sub->en_title) ?></td>
                            <td class="text-center"><?= htmlspecialchars($sub->service_key) ?></td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/admin/services/edit/<?= $sub->id ?>" class="btn btn-sm btn-warning">
                                    ✏️ <?= __('edit') ?>
                                </a>

                                <form action="<?= BASE_URL ?>/admin/services/delete/<?= $sub->id ?>" method="post" class="d-inline"
                                      onsubmit="return confirm('<?= __('confirm_delete_service') ?>')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        🗑️ <?= __('delete') ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php $count++ ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (!empty($pagination) && $pagination['last_page'] > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&per_page=<?= $per_page ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

