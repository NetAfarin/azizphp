<?= flash('success') ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label class="form-label"><?= __('user_type') ?></label>
        <select name="user_type" id="user_type_select" class="form-select" >
            <?php foreach ($userTypes as $type): ?>
                <option value="<?= $type->id ?>" <?= ($user->user_type == $type->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($type->title) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3" id="employee_services_section" style="display: none;">
        <label class="form-label"><?= __('employee_service_list') ?></label>
        <select name="employee_services[]"
                class="form-select js-example-basic-multiple"
                multiple="multiple"
                data-mdb-filter="true">
            <?php foreach ($groupedServices as $group): ?>
                <optgroup label="<?= htmlspecialchars($group['parent']->fa_title) ?>">
                    <?php foreach ($group['children'] as $service): ?>
                        <option value="<?= $service->id ?>" <?= in_array($service->id, $selectedServiceIds) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($service->fa_title) ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
        <small class="form-text text-muted"><?= __('select_services_for_employee') ?></small>
    </div>

    <div id="services_table_wrapper" class="mb-3" style="display:<?=sizeof($employeeServicesData)>0?"block":"none" ?>;">
        <label class="form-label"><?= __('services_price_duration') ?></label>
        <table class="table table-bordered" id="services_table">
            <thead>
            <th><?= __('service_name') ?></th>
            <th><?= __('price') ?></th>
            <th><?= __('duration') ?></th>
            <th><?= __('delete') ?></th>
            </thead>
            <tbody>
            <?php foreach ($employeeServicesData as $es): ?>
                <tr id="row-<?= $es->service_id ?>">
                    <td><?= htmlspecialchars($es->title) ?></td>
                    <td>
                        <input type="number" step="0.01" min="0"
                               class="form-control"
                               name="service_prices[<?= $es->service_id ?>]"
                               value="<?= htmlspecialchars($es->price) ?>"
                               required>
                    </td>
                    <td>
                        <select name="service_durations[<?= $es->service_id ?>]" class="form-select" required>
                            <?php foreach ($durations as $d): ?>
                                <option value="<?= $d->id ?>" <?= $d->id == $es->estimated_duration ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d->title) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row" data-id="<?= $es->service_id ?>">✖</button>
                    </td>
                </tr>
            <?php endforeach; ?>            </tbody>
        </table>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('first_name') ?></label>
        <input type="text" name="first_name" class="form-control"
               value="<?= htmlspecialchars($user->first_name ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('last_name') ?></label>
        <input type="text" name="last_name" class="form-control"
               value="<?= htmlspecialchars($user->last_name ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label"><?= __('phone_number') ?></label>
        <input type="text" name="phone_number" class="form-control"
               value="<?= htmlspecialchars($user->phone_number ?? '') ?>">
    </div>
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
            <?= ( $user->is_active == 1) ? 'checked' : '' ?>>
        <label class="form-check-label" for="is_active">
            <?= __('is_active') ?>
        </label>
    </div>
    <button class="btn btn-primary"><?= __('save_changes') ?></button>
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-danger"><?= __('cancel') ?></a>
</form>
<script src="<?= BASE_URL ?>/js/edit-user.js"></script>