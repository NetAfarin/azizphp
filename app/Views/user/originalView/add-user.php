<div class="d-lg-none">
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
            aria-controls="sidebar">
        <i class="fa fa-bars"></i>
    </button>
</div>
<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>


                <div class="mt-5"><h4><?= __("basic_data") ?></h4></div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="username"><?= __("name") ?> <span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="username">
                    </div>
                    <div class="col-6">
                        <label for="lastname"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="lastname">
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="national-code"><?= __("national_code") ?><span
                                    class="bullet-color"> *</span></label>
                        <input type="text" class="form-control ltr-input" id="national-code">
                    </div>
                    <div class="col-6">
                        <label for="birthday-date"><?= __("birth_date") ?><span class="bullet-color"> *</span></label>
<!--                        <input type="text" class="form-control" id="birthday-date">-->
                        <input type="text" id="birth_date_picker" class="form-control" data-old="<?= old('birth_date') ?>">

                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="phone-number"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control ltr-input" id="phone-number">
                    </div>
                    <div class="col-6">
                        <label for="userRole"><?= __("role") ?><span class="bullet-color"> *</span></label>
                        <select class="js-example-basic-single w-100" name="state" id="userRole">
                            <?php foreach ($userRole as $role): ?>
                            <option id="<?= $role->id ?>"><?= ($lang =="fa" ? $role->title : $role->en_title)?></option>
                        <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <label for="user-role"><?= __("postal_address") ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" id="user-role">
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="row mt-5"><h4><?= __("job_information") ?></h4></div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="break_time" class="form-label"><?= __("break_time") ?></label>
                        <select class="js-example-basic-single w-100" name="state" id="break_time">
                            <option value="1">12 و نیم الی 13 ونیم</option>
                            <option value="2">13 و نیم الی 14 ونیم</option>
                            <option value="3">14 و نیم الی 15 ونیم</option>
                        </select>

                    </div>
                    <div class="col-6">
                          <div class="mb-3" id="employee_services_section" >

                        <label for="multiple-select-field" class="form-label"><?= __("skills") ?><span
                                    class="bullet-color"> *</span></label>
                        <select class="form-select" id="multiple-select-field" multiple>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service->id ?>">
                                    <?= ($lang =="fa" ? $service->fa_title : $service->en_title) ?>
                                </option>
                            <?php endforeach;?>
                        </select>

                          </div>
                    </div>
                    <div id="services_table_wrapper" class="mt-3"></div>
                </div>
                <div class="mt-5" id="employeeSection">
                    <span id="shiftTitle"><?= __("shift") ?></span>
                    <div class="form-check  switch-input form-switch mt-2">
                        <input class="switch-input form-check-input" type="checkbox" role="switch" id="switchBox">
                        <label class="switch-input form-check-label" for="switchBox"></label>
                    </div>
                    <div class="table-wrapper mt-3" id="workTable" style="display: none">
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
                                <tr>
                                    <td>شنبه</td>
                                    <td>
                                        <i class="fa-light fa-clock icon-color pe-1"></i>
                                        <span>8:00</span>
                                    </td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    تعطیل
                                                </label>
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="flexCheckDefault">
                                            </div>
                                        </div>

                                    </td>

                                </tr>
                                <tr>
                                    <td>یکشنبه</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault2">
                                                    تعطیل
                                                </label>
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="flexCheckDefault2">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>دوشنبه</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault3">
                                                    تعطیل
                                                </label>
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="flexCheckDefault3">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>سه شنبه</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault4">
                                                    تعطیل
                                                </label>
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="flexCheckDefault4">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>چهارشنبه</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault5">
                                                    تعطیل
                                                </label>
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="flexCheckDefault5">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>پنج شنبه</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault6">
                                                    تعطیل
                                                </label>
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="flexCheckDefault6">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>جمعه</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                    <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="flexCheckDefault7">
                                                    تعطیل
                                                </label>
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="flexCheckDefault7">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary mt-5 px-5" type="submit">افزودن</button>

</div>
</div>
</body>
<script> window.durations = <?= json_encode(array_map(fn($d) => ['id' => $d->id, 'title' => $d->title], $durations), JSON_UNESCAPED_UNICODE) ?>;
</script>
<script>

    $(document).ready(function() {

        $('#multiple-select-field').on('change', function() {
            const selectedOptions = $(this).select2('data');
            const $wrapper = $('#services_table_wrapper');

            if(selectedOptions.length === 0){
                $wrapper.html('');
                return;
            }

            let tableHTML = '<div class="table-wrapper"><table class="table transparent custom-table   table-bordered"><thead><tr><th>نام سرویس</th><th>قیمت (تومان)</th><th>مدت زمان</th><th>حذف</th></tr></thead><tbody></div>';

            selectedOptions.forEach(opt => {
                let durationOptionsHTML = '';
                durations.forEach(d => {
                    durationOptionsHTML += `<option value="${d.id}">${d.title}</option>`;
                });

                tableHTML += `
                <tr>
                    <td>${opt.text}</td>
                    <td>
                        <input type="text" min="0" class="form-control" name="service_prices" required>
                    </td>
                    <td>
                    <select name="service_durations" class=" js-duration-select" required>
                            ${durationOptionsHTML}
                        </select>
                    </td>
                    <td>
                        <i class="fa fa-close remove-row icon-color"></i>
                    </td>
                </tr>`;
            });

            tableHTML += '</tbody></table>';
            $wrapper.html(tableHTML);
            $('.js-duration-select').select2({
                placeholder: "انتخاب مدت زمان",
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        });

        // حذف ردیف‌ها
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

    });

</script>
<script src="<?= asset('/js/register-user.js') ?>"></script>
