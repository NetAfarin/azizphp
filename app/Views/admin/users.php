<?= flash('success') ?>
<?= flash('error') ?>
<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/home/index" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
<h2><?= __('users_list') ?></h2>

<table class="table table-bordered table-striped">
    <thead class="table-light">
    <tr>
        <th>#</th>
        <th><?= __('first_name') ?></th>
        <th><?= __('last_name') ?></th>
        <th><?= __('phone_number') ?></th>
        <th><?= __('user_type') ?></th>
        <th><?= __('services') ?></th>
        <th><?= __('is_active') ?></th>
        <th><?= __('actions') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $index => $user): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= htmlspecialchars($user->first_name) ?></td>
            <td><?= htmlspecialchars($user->last_name) ?></td>
            <td><?= htmlspecialchars($user->phone_number) ?></td>
            <td><?= htmlspecialchars($user->getRoleTitle()) ?></td>
            <td>
                <?php if ($user->getUserType()->en_title === 'employee'): ?>
                    <?php foreach ($user->getEmployeeServices() as $service): ?>
                        <span class="badge bg-info text-dark">
                            <?= htmlspecialchars($service->fa_title ?? $service->title ?? '-') ?>
                        </span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
            </td>
            <td><span class="badge bg-<?= $user->is_active ? 'success' : 'secondary' ?>">
                    <?= $user->is_active ? __('active') : __('inactive') ?></span>
            </td>
            <td>
                <a href="<?= BASE_URL ?>/admin/user/edit/<?= $user->id ?>"
                   class="btn btn-sm btn-warning">✏️ <?= __('edit') ?></a>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/user/delete/<?= $user->id ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('<?= __('confirm_delete') ?>')">🗑️ <?= __('delete') ?></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
