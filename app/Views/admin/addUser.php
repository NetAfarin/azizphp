<?php use App\Models\UserType;

//if (!empty($errors)): ?>
<!--    <div class="alert alert-danger">-->
<!--        <ul>-->
<!--            --><?php //foreach ($errors as $e): ?>
<!--                <li>--><?php //= htmlspecialchars($e) ?><!--</li>-->
<!--            --><?php //endforeach; ?>
<!--        </ul>-->
<!--    </div>-->
<?php //endif; ?>

<?php
$publicErrors = array_filter($errors ?? [], fn($k) => is_numeric($k), ARRAY_FILTER_USE_KEY);
if (!empty($publicErrors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $key => $fieldErrors): ?>
                <?php if (is_numeric($key)): ?>
                    <li><?= htmlspecialchars($fieldErrors) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= __('register_success') ?> ✅
    </div>
<?php endif; ?>
<div class="mb-3" style="text-align: <?= $dir === 'rtl' ? 'right' : 'left' ?>;">
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-primary">
        <span style="display:inline-block; transform: rotate(<?= $dir === 'rtl' ? '180' : '0' ?>deg);">⬅️</span>
        <?= __('back_to_dashboard') ?>
    </a>
</div>
<h1><?= $title; ?></h1>


<form method="post" class="row g-3">
    <?= csrf_field() ?>
    <?php if ($userType->id === UserType::EMPLOYEE): ?>
        <div class="mb-3" id="employee_services_section">
            <label class="form-label"><?= __('employee_service_list') ?></label>
            <select name="employee_services[]"
                    class="form-select js-example-basic-multiple"
                    multiple="multiple"
                    data-mdb-filter="true">

                <?php foreach ($groupedServices as $group): ?>
                    <optgroup label="<?= htmlspecialchars($group['parent']->fa_title) ?>">
                        <?php foreach ($group['children'] as $service): ?>
                            <option value="<?= $service->id ?>" <?= (!empty(old('employee_services')) && in_array($service->id, old('employee_services'))) ? 'selected' : '' ?>>
                                <!--                                --><?php //if ((!empty(old('employee_services')) && in_array($service->id, old('employee_services')))){
                                //                                    $employeeServicesData[] = $service;
                                //                                } ?>
                                <?= htmlspecialchars($service->fa_title) ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted"><?= __('select_services_for_employee') ?></small>
        </div>
        <div id="services_table_wrapper" class="mb-3">
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

                    <tr id="row-<?= $es->id ?>">
                        <td><?= htmlspecialchars($es->title) ?></td>
                        <td>
                            <input type="number" step="0.01" min="0"
                                   class="form-control"
                                   name="service_prices[<?= $es->id ?>]"
                                   value="<?= htmlspecialchars($prices[$es->id]) ?>"
                                   required>
                        </td>
                        <td>
                            <select name="service_durations[<?= $es->id ?>]" class="form-select" required>

                                <?php foreach ($durations as $d): ?>
                                    <option value="<?= $d->id ?>" <?= $d->id == old('service_durations')[$es->id] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($d->title) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row"
                                    data-id="<?= $es->service_id ?>">✖
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="first_name"><?= __('first_name') ?></label>
        <input type="text" class="form-control" name="first_name" id="first_name"
               value="<?= old('first_name') ?>">
        <?php if (!empty($errors['first_name'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['first_name'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="last_name"><?= __('last_name') ?></label>
        <input type="text" class="form-control" name="last_name" id="last_name"
               value="<?= old('last_name') ?>">
        <?php if (!empty($errors['last_name'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['last_name'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="phone_number"><?= __('phone_number') ?></label>
        <input type="text" class="form-control" name="phone_number" id="phone_number"
               value="<?= old('phone_number') ?>">
        <?php if (!empty($errors['phone_number'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['phone_number'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password"><?= __('password') ?></label>
        <input type="password" class="form-control" name="password" id="password"
               value="<?= old('password') ?>">
        <?php if (!empty($errors['password'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['password'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="password_confirmation"><?= __('password_confirmation') ?></label>
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
               value="<?= old('password_confirmation') ?>">
        <?php if (!empty($errors['password_confirmation'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['password_confirmation'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="birth_date"><?= __('birth_date') ?></label>
        <input type="hidden" name="birth_date" id="birth_date" value="<?= old('birth_date') ?>">
        <input type="text" id="birth_date_picker" class="form-control" data-old="<?= old('birth_date') ?>">

        <?php if (!empty($errors['birth_date'])): ?>
            <div class="text-danger small"><?= htmlspecialchars($errors['birth_date'][0]) ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary"><?= __('submit') ?></button>
</form>
<script>
    window.durations = <?= json_encode(array_map(fn($d) => ['id' => $d->id, 'title' => $d->title], $durations), JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= asset('/js/register-user.js') ?>"></script>
<!--<script src="--><?php //= asset('/js/edit-user.js') ?><!--"></script>-->






