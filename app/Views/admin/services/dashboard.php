<?php
use App\Models\Service;
?>
<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/admin/panel" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
<div class="d-flex justify-content-between align-items-center">

<form method="get" class="mb-3 d-flex align-items-center gap-2">
    <label for="per_page" class="form-label mb-0"><?= __('per_page') ?>:</label>
    <select name="per_page" id="per_page" class="form-select w-auto" onchange="this.form.submit()">
        <?php foreach ($allowedPerPage as $opt): ?>
            <option value="<?= $opt ?>" <?= $per_page === $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
    </select>
    <noscript><button type="submit" class="btn btn-primary btn-sm"><?= __('apply') ?></button></noscript>
</form>
<form method="get" class="d-flex align-items-center gap-2 mb-2">
    <label for="search" class="form-label mb-0"><?= __('search') ?>:</label>
    <input type="text" id="search" name="search" class="form-control"
           value="<?= htmlspecialchars($search) ?>" placeholder="<?= __('search_services') ?>">
    <button type="submit" class="btn btn-primary btn-sm">🔍 <?= __('apply') ?></button>
    <?php if (!empty($per_page)): ?>
        <input type="hidden" name="per_page" value="<?= $per_page ?>">
    <?php endif; ?>
    <?php if (!empty($search)): ?>
        <a href="?page=1&per_page=<?= $per_page ?>" class="btn btn-outline-secondary btn-sm">✖ <?= __('clear') ?></a>
    <?php endif; ?>
</form>
</div>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><?= __('categories') ?></h4>
        <div>
            <a href="<?= BASE_URL ?>/admin/services/create" class="btn btn-light btn-sm">➕ <?= __('add_service') ?></a>
            <a href="<?= BASE_URL ?>/admin/services/category/create" class="btn btn-light btn-sm">➕ <?= __('add_category') ?></a>
        </div>
    </div>
    <div class="card-body">
        <?php
        $startNumber = (($pagination['current_page'] - 1) * $per_page) + 1;
            $count = $startNumber;
        ?>
        <?php if (empty($services)): ?>
            <div class="alert alert-info"><?= __('category_not_found') ?></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">
                            <a href="<?= $sortTitleUrl ?>" class="text-decoration-none text-dark">
                                <?= __('title').($sortBy=='title'?($sortOrder=='desc'?'⬇️': '⬆️'):'') ?>
                                <?php if ($sortBy === 'title'): ?>
                                    <?php if ($sortOrder === 'asc'): ?>
                                        <i class="fas fa-sort-up"></i>
                                    <?php else: ?>
                                        <i class="fas fa-sort-down"></i>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i class="fas fa-sort"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th class="text-center"><?= __('category') ?></th>
                        <th class="text-center"><?= __('service_count') ?></th>
                        <th class="text-center"><?= __('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($services as $ser): ?>
                        <tr>
                            <td class="text-center"><?= htmlspecialchars($count) ?></td>
                                <td class="text-center" style="padding-right: 30px;">
                                    <?= htmlspecialchars($ser->title) ?>
                                </td>
                            <td class="text-center" style="padding-right: 30px;">
                                   <?php if($ser->parent_id !=0 ):?>
                                       <?= htmlspecialchars( $ser->parent_title) ?>
                                <?php else:?>
                                   -
                                <?php endif;?>
                            </td>
                            <?php if ($ser->parent_id == 0): ?>
                                <td class="text-center">
                                    <?php
                                    $childCount = Service::query()->where('parent_id', "=" , $ser->id)->where("deleted" , "=" ,"0")->get();
                                    ?>
                                    <?= sizeof(($childCount)) ?>
                                </td>
                            <?php else: ?>
                                <td class="text-center">-</td>
                            <?php endif; ?>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>/admin/services/category/edit/<?= $ser->id ?>" class="btn btn-sm btn-warning">
                                    ✏️ <?= __('edit') ?>
                                </a>

                                <form action="<?= BASE_URL ?>/admin/services/category/delete/<?= $ser->id ?>" method="post" class="d-inline"
                                      onsubmit="return confirm('<?= __('confirm_delete_category') ?>')">
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
                            <a class="page-link" href="?page=<?= $i ?>&per_page=<?= $per_page ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($sortBy) ? '&sortby=' . urlencode($sortBy).('&sortorder='.$sortOrder) : '' ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
