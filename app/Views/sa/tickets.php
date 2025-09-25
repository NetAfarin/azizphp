<?= flash('success') ?>
<?= flash('error') ?>

<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/sa/dashboard" class="btn btn-outline-primary">
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
    <noscript>
        <button type="submit" class="btn btn-primary btn-sm"><?= __('apply') ?></button>
    </noscript>
</form>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><?= $title ?></h4>
        <a href="<?= BASE_URL ?>/sa/ticket/create" class="btn btn-light btn-sm">➕ <?= __('add_ticket') ?></a>
    </div>
    <div class="card-body">

        <?php if (empty($tickets)): ?>
            <div class="alert alert-info"><?= __('no_tickets_found') ?></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?= __('user_name') ?></th>
                        <th><?= __('salon_name') ?></th>
                        <th><?= __('title') ?></th>
                        <th><?= __('description') ?></th>
                        <th><?= __('status') ?></th>
                        <th><?= __('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?= htmlspecialchars($ticket->id) ?></td>
                            <td><?= htmlspecialchars($ticket->user_name) ?></td>
                            <td><?= htmlspecialchars($ticket->salon_name) ?></td>
                            <td><?= htmlspecialchars($ticket->title) ?></td>
                            <td><?= htmlspecialchars($ticket->description) ?></td>
                            <td>
                                    <span class="badge <?= $ticket->status_class ?>">
                                        <?= htmlspecialchars($ticket->status_name) ?>
                                    </span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/sa/ticket/edit/<?= $ticket->id ?>" class="btn btn-sm btn-warning">
                                    ✏️ <?= __('edit') ?>
                                </a>

                                <form action="<?= BASE_URL ?>/sa/ticket/delete/<?= $ticket->id ?>" method="post" class="d-inline" onsubmit="return confirm('<?= __('confirm_delete') ?>')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        🗑️ <?= __('delete') ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
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
