<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><?= htmlspecialchars($salon->first_name . ' ' . $salon->last_name) ?></h4>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong><?= __('birth_date') ?>:</strong> <?= htmlspecialchars($salon->birth_date) ?></li>
            <li class="list-group-item"><strong><?= __('phone_number') ?>:</strong> <?= htmlspecialchars($salon->phone_number) ?></li>
            <li class="list-group-item"><strong><?= __('role') ?>:</strong> <?= htmlspecialchars($salon->getRoleTitle() ?? '') ?></li>
            <li class="list-group-item"><strong><?= __('active') ?>:</strong>
                <span class="badge <?= $salon->is_active ? 'bg-success' : 'bg-danger' ?>">
            <?= $salon->is_active ? __('yes') : __('no') ?>
        </span>
            </li>
            <li class="list-group-item"><strong><?= __('register_datetime') ?>:</strong> <?= htmlspecialchars($salon->register_datetime) ?></li>
        </ul>

    </div>
</div>
<?php include __DIR__ . '/../layout/backButton.php'; ?>
