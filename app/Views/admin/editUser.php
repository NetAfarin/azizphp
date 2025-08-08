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
        <select name="user_type" id="user_type_select" class="form-select">
            <?php foreach ($userTypes as $type): ?>
                <option value="<?= $type->id ?>" <?= ($user->user_type == $type->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($type->title) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3" id="employee_services_section" style="display: none;">
        <label class="form-label"><?= __('employee_service_list') ?></label>
        <select name="employee_services[]" multiple class="form-select"  data-mdb-filter="true">
            <?php foreach ($employeeServices as $service): ?>
                <option value="<?= $service->id ?>" <?= in_array($service->id, $selectedServiceIds) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($service->fa_title) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small class="form-text text-muted"><?= __('select_services_for_employee') ?></small>
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
</form>

<script>
    const userTypeSelect = document.getElementById('user_type_select');
    const employeeServicesSection = document.getElementById('employee_services_section');

    function toggleEmployeeServices() {
        const selectedType = userTypeSelect.options[userTypeSelect.selectedIndex].text.toLowerCase();
        if (selectedType.includes('کارمند') || selectedType.includes('employee')) {
            employeeServicesSection.style.display = 'block';
        } else {
            employeeServicesSection.style.display = 'none';
        }
    }

    userTypeSelect.addEventListener('change', toggleEmployeeServices);
    toggleEmployeeServices();
</script>
