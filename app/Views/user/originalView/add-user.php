<div class="d-lg-none">
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
            aria-controls="sidebar">
        <i class="fa fa-bars"></i>
    </button>
</div>
<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>

 <form method="post">
     <?= csrf_field() ?>
     <div class="mt-5"><h4><?= __("basic_data") ?></h4></div>
     <div class="row mt-5">
         <div class="col-6">
             <label for="username"><?= __("name") ?> <span class="bullet-color"> *</span></label>
             <input type="text" class="form-control" id="username" name="firstname">
         </div>
         <div class="col-6">
             <label for="lastname"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
             <input type="text" class="form-control" id="lastname" name="lastname">
         </div>
     </div>
     <div class="row mt-5">
         <div class="col-6">
             <label for="national-code"><?= __("national_code") ?><span
                         class="bullet-color"> *</span></label>
             <input type="text" class="form-control ltr-input" id="national-code" name="nationalCode">
         </div>
         <div class="col-6">
             <label for="birthday-date"><?= __("birth_date") ?><span class="bullet-color"> *</span></label>
             <!--                        <input type="text" class="form-control" id="birthday-date">-->
             <input type="text" id="birth_date_picker" class="form-control" name="birth_date">

         </div>
     </div>
     <div class="row mt-5">
         <div class="col-6">
             <label for="phone-number"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
             <input type="text" class="form-control ltr-input" id="phone-number" name="phoneNumber">
         </div>
         <div class="col-6">
             <label for="userRole"><?= __("role") ?><span class="bullet-color"> *</span></label>
             <select class="js-example-basic-single w-100"  id="userRole" name="role">
                 <?php foreach ($userRole as $role): ?>
                     <option value="<?= $role->id ?>">
                         <?=( $role->title) ?>
                     </option>
                 <?php endforeach;?>
             </select>
         </div>
     </div>
     <div class="row mt-5">
         <div class="col-12">
             <label for="address"><?= __("postal_address") ?><span class="bullet-color"> *</span></label>
             <input type="text" class="form-control" id="user-role" name="address">
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
                 <select class="form-select" id="multiple-select-field" multiple name="service[]">
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
             <input class="switch-input form-check-input" type="checkbox" role="switch" id="switchBox" name="followSalon" checked>
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
<!--                             <i class="fa-light fa-clock icon-color pe-1"></i>-->
                             <input type="time" name="startTime[]" class="form-control" >
                         </td>
                         <td>
                             <input type="time" name="endTime[]" class="form-control" >

                         </td>
                         <td>
                             <div class="d-flex justify-content-center">
                                 <div class="form-check">
                                     <label class="form-check-label" for="holiday-<?= $index ?>">
                                         تعطیل
                                     </label>
                                     <input class="form-check-input" type="checkbox" id="holiday-<?= $index ?>" name="holiday[<?= $index ?>]" value="1">
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
     <button class="btn btn-primary mt-5 px-5" type="submit">افزودن</button>
 </form>


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

            let tableHTML = '<div class="table-wrapper"><table class="table transparent custom-table ""><thead><tr><th>نام سرویس</th><th>قیمت (تومان)</th><th>مدت زمان</th><th>حذف</th></tr></thead><tbody></div>';

            selectedOptions.forEach(opt => {
                let durationOptionsHTML = '';
                durations.forEach(d => {
                    durationOptionsHTML += `<option value="${d.id}">${d.title}</option>`;
                });

                tableHTML += `
                <tr>
                    <td>${opt.text}</td>
                    <td>
                        <input type="text" min="0" class="form-control" name="service_prices[]" required>
                    </td>
                    <td>
                    <select name="service_durations[]" class=" js-duration-select" required>
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
