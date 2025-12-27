<?php use App\Models\UserType;
include BASE_PATH . '/app/Views/components/layout.php'; ?>
<form method="post">
    <?= csrf_field() ?>
    <div class="mt-5"><h4><?= __("basic_data") ?></h4></div>
    <div class="row mt-5">
        <div class="col-6">
            <label for="username"><?= __("name") ?> <span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="username" name="first_name" value="<?=$user->first_name?>">
            <?php if (!empty($errors['first_name'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['first_name'][0]) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-6">
            <label for="lastname"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="lastname" name="last_name" value="<?=$user->last_name?>">
            <?php if (!empty($errors['last_name'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['last_name'][0]) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-6">
            <label for="national-code"><?= __("national_code") ?><span
                    class="bullet-color"> *</span></label>
            <input type="text" class="form-control ltr-input" id="national-code" name="national_code" value="<?=$user->national_code?>">
            <?php if (!empty($errors['national_code'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['national_code'][0]) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-6">
            <label for="birthday-date"><?= __("birth_date") ?><span class="bullet-color"> *</span></label>
            <div class="input-with-icon-left">
                <input type="text" id="birth_date_picker" class="form-control" name="birth_date" value="<?= ($user->birth_date != "") ? toJalali($user->birth_date)['date'] : "0000-00-00"?> ">
                <i class="fa fa-calendar icon-color"></i>
            </div>
            <?php if (!empty($errors['birth_date'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['birth_date'][0]) ?></div>
            <?php endif; ?>

        </div>
    </div>
    <div class="row mt-5">
        <div class="col-6">
            <label for="phone-number"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control ltr-input" id="phone_number" name="phone_number" value="<?= $user->phone_number?>">
            <?php if (!empty($errors['phone_number'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['phoneNumber'][0]) ?></div>
            <?php endif; ?>
        </div>
        <div class="col-6">
            <label for="userRole"><?= __("role") ?><span class="bullet-color"> *</span></label>
            <select class="js-example-basic-single w-100"  id="userRole" name="role" disabled>
                <?php foreach ($userTypes as $role): ?>
                    <option value="<?= $role->id ?>"  <?= ($role->id == $user->user_type) ? 'selected' : '' ?>>
                        <?= ($lang == "fa") ? htmlspecialchars($role->title ?? '') : htmlspecialchars($role->en_title) ?>
                    </option>
                <?php endforeach;?>
            </select>
            <?php if (!empty($errors['role'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['role'][0]) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <label for="address"><?= __("postal_address") ?><span class="bullet-color"> *</span></label>
            <input type="text" class="form-control" id="user-role" name="address" value="<?= $user->postal_address?>">
            <?php if (!empty($errors['address'])): ?>
                <div class="text-danger small"><?= htmlspecialchars($errors['address'][0]) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div id="employeeSection">
        <div class="row mt-5">
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="row mt-5"><h4><?= __("job_information") ?></h4></div>
        <div class="row mt-5" >
            <div class="col-6">
                <label for="break_time" class="form-label"><?= __("break_time") ?></label>
                <select class="js-example-basic-single w-100" name="breakTime" id="break_time">
                    <option value="1">12 و نیم الی 13 ونیم</option>
                    <option value="2">13 و نیم الی 14 ونیم</option>
                    <option value="3">14 و نیم الی 15 ونیم</option>
                </select>

            </div>
            <div class="col-6">
                <div class="mb-3" id="employee_services_section" >
                    <label for="multiple-select-field" class="form-label"><?= __("skills") ?><span
                            class="bullet-color"> *</span></label>
                    <select class="js-example-basic-single" id="multi-services" multiple name="services[]" >
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service->id ?>" <?= in_array($service->id, $selectedServiceIds) ? 'selected' : '' ?>>
                                <?= ($lang =="fa" ? $service->fa_title : $service->en_title) ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                    <?php if (!empty($errors['services'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['services'][0]) ?></div>
                    <?php endif; ?>

                </div>
            </div>
            <div id="services_table_wrapper" class="mt-3">

                <div class="table-wrapper">
                    <table class="table transparent custom-table">
                        <thead>
                        <tr>
                            <th><?=__("service_title") ?></th>
                            <th><?=__("price_toman") ?></th>
                            <th><?=__("duration") ?></th>
                            <th><?=__("delete") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($employeeServices as $service): ?>
                            <tr data-id="<?= $service->service_id ?>">
                                <td><?= $service->title ?></td>
                                <td>
                                    <input type="number" name="service_prices[<?= $service->service_id ?>]" value="<?= $service->price ?>" class="form-control">
                                </td>
                                <td>
                                    <select class="duration-select" name="service_durations[<?= $service->service_id ?>]">
                                        <?php foreach ($durations as $d): ?>
                                            <option value="<?= $d->id ?>" <?= ($d->id == $service->duration_id) ? 'selected' : '' ?>>
                                                <?= ($lang == 'fa') ? $d->title : $d->en_title ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><i class="fa fa-close remove-row icon-color" style="cursor:pointer;"></i></td>
                            </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-5" >
            <span id="shiftTitle"><?= __("shift") ?></span>
            <div class="form-check  switch-input form-switch mt-2">
                <input class="switch-input form-check-input" type="checkbox" role="switch" id="switchBox" name="followSalon" <?= ($user->follow_shift_from_salon ==  1) ? "checked" : "" ?>>
                <label class="switch-input form-check-label" for="switchBox"></label>
            </div>
            <div class="table-wrapper mt-3" id="workTable">
                <div class="table-responsive ">
                    <table class="table custom-table">
                        <thead class="table-primary ">
                        <tr>
                            <th><?= __("day") ?></th>
                            <th><?= __("start_work") ?></th>
                            <th><?= __("end_work") ?></th>
                            <th><?= __("status") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($days as $index => $day): ?>
                            <tr>
                                <td><?= $day ?></td>
                                <td>
                                    <div class="custom-time-container time-with-icon">
                                        <input type="time" name="startTime[<?= $index ?>]" id="startTime-<?= $index ?>" class="form-control time-input" value="<?= $timesForView[$index]['start_time'] ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-time-container time-with-icon">
                                        <input type="time" name="endTime[<?= $index ?>]" id="endTime-<?= $index ?>" class="form-control time-input" value="<?= $timesForView[$index]['end_time'] ?>">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="form-check tick">
                                            <label class="form-check-label m-0" for="holiday-<?= $index ?>">
                                                <?=__("close")?>
                                            </label>
                                            <input class="form-check-input holiday-checkbox" type="checkbox"
                                                   id="holiday-<?= $index ?>"
                                                   name="holiday[<?= $index ?>]"
                                                   data-id="<?= $index ?>"
                                                <?= ($timesForView[$index]['off_day'] == 1) ? 'checked' : '' ?>>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary mt-5 px-5" type="submit"><?=__("edit_user")?></button>
</form>


</div>
</div>
<script>
    const durations = [];
    <?php  foreach ($durations as $index => $duration): ?>
    durations.push(<?php echo $duration->toJson(); ?>)
    <?php endforeach; ?>
    var lang = "<?= $lang ?>";
</script>
<script src="<?= asset('/js/user/edit-user.js') ?>"></script>
