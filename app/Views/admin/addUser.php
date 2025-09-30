
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
<h1><?= $title;?></h1>


<?php if (!empty($success)): ?>
    <div class="alert alert-success">
        <?= __('register_success') ?> ✅
    </div>
<?php endif; ?>

<form method="post" class="row g-3" >
    <?= csrf_field() ?>
    <?php  if ($userType->id === UserType::EMPLOYEE): ?>
        <div class="mb-3" id="employee_services_section" >
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
               value="<?= old('password_confirmation') ?>" >
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
<script src="<?= asset('/js/register-user.js') ?>"></script>






