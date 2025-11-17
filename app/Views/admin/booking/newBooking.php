
<?php
use App\Models\Service;
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
<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div class="col-12 mt-5 bg-white shadow-md rounded p-3">
    <div class="d-flex justify-content-around align-items-center">
        <div class="tab-item text-center d-flex" onclick="switchTab(0)">
            <div class="tab-circle" id="circle0"></div>
            <div class="px-2">اولویت کارمندان</div>
        </div>
        <div class="tab-item text-center d-flex" onclick="switchTab(1)">
            <div class="tab-circle" id="circle1"></div>
            <div class="px-2">اولویت زمان</div>
        </div>
    </div>
</div>
    <div class="col-12 mt-5 bg-white shadow-md rounded p-4">
        <div class="tab-content ">
            <div id="content0">
                <h6 class="fw-bold"><?= __("customer_information")?></h6>
                <form method="post" >
                    <?= csrf_field() ?>
                    <div class="input-group mt-5">
                        <input type="text" class="form-control border-end-0" name="search" id="search"
                               value="<?= htmlspecialchars($search) ?>" placeholder="<?= __("search_customer_mobile") ?>" >
                            <button class="btn btn-search border-start-0" type="button" id="btn-search" >
                                <i class='fas fa-search'></i>
                            </button>
                    </div>
                    <div class="mt-5">
                    <label for="phone_number"><?= __('phone_number') ?><span class="bullet-color"> *</span></label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number"
                           value="">
                    <?php if (!empty($errors['phone_number'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['phone_number'][0]) ?></div>
                    <?php endif; ?>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="first_name"><?= __('first_name') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" name="first_name" id="first_name">
                        <?php if (!empty($errors['first_name'])): ?>
                            <div class="text-danger small"><?= htmlspecialchars($errors['first_name'][0]) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <label for="last_name"><?= __('last_name') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" name="last_name" id="last_name">
                        <?php if (!empty($errors['last_name'])): ?>
                            <div class="text-danger small"><?= htmlspecialchars($errors['last_name'][0]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <h6 class="fw-bold mt-5"><?= __("choose_service")?></h6>
                <div class="mt-4">
                    <label for="service"><?= __('service') ?><span class="bullet-color"> *</span></label>
                        <select  class="js-example-basic-single form-select w-100" id="service_id" name="service">
                            <?php foreach ($services as $ser): ?>
                                <option value="<?= $ser->id ?>"><?= $ser->fa_title ?></option>
                            <?php endforeach; ?>
                        </select>
                </div>
                <h6 class="fw-bold mt-5"><?= __("choose_employee")?></h6>
                <div class="mt-4">
                    <label for="employee"><?= __('employee') ?><span class="bullet-color"> *</span></label>
                    <select class="js-example-basic-single form-select w-100" name="employee" id="employee_select">
                    </select>
                </div>
                <h6 class="fw-bold mt-5"><?= __("choose_time")?></h6>
                <div class="mt-4">
                    <div class="row">
                        <div class="col-6">
                            <label for="birthday-employee_date"><?= __("date") ?><span class="bullet-color"> *</span></label>
                            <div class="input-with-icon-left">
                                <select class="js-example-basic-single form-select w-100" name="date"  id="employee_date">
                                </select>
                                <i class="fa fa-calendar icon-color"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="time"><?= __('time') ?><span class="bullet-color"> *</span></label>
                            <select class="js-example-basic-single form-select w-100" name="time"  id="employee_time">
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary mt-4" type="submit" id="submitButton">نایید</button>
                </form>
            </div>
            <div id="content1" style="display:none;">این محتوای اولویت زمان است</div>
        </div>
    </div>
<div class="modal fade borderless-modal" id="reserveModal" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body custom-modal-body mt-4 mb-4">
                <h4 class="fw-bold">این ساعت قبلا رزرو شده است</h4>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script>
    $(document).ready(function (){
        $('#service_id').change(function() {
            var serviceId = $(this).val();
            if (!serviceId) {
                console.log('سرویس انتخاب نشده');
                return;
            }
            var url = `${BASE_URL}/admin/bookings/get/` + serviceId;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#employee_time, #employee_date').empty();
                    $('#employee_select').empty().append(
                        (response.data || []).map((item, index) =>
                            $('<option>', {
                                value: item.id,
                                text: item.first_name,
                                selected: index === 0,
                            })
                        )
                    ).trigger('change');
                },
                error: function(xhr, status, error) {
                    console.log('❌ خطا در دریافت پاسخ:');
                    console.log('   وضعیت:', status);
                    console.log('   خطا:', error);
                    console.log('   پاسخ سرور:', xhr.responseText);
                    console.log('   کد وضعیت:', xhr.status);
                }
            });
        });
        $('#employee_select').change(function (){
            var employeeId = $(this).val();
            var url = `${BASE_URL}/admin/bookings/getEmployeeDate/` + employeeId;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#employee_date').empty().append(
                        (response.data || []).map((item, index) =>
                            $('<option>', {
                                value: item.id,
                                text: item.date,
                                selected: index === 0,
                            })
                        )
                    ).trigger('change');
                },

                error: function(xhr, status, error) {
                    console.log('❌ خطا در دریافت پاسخ:');
                    console.log('   وضعیت:', status);
                    console.log('   خطا:', error);
                    console.log('   پاسخ سرور:', xhr.responseText);
                    console.log('   کد وضعیت:', xhr.status);
                }
            });
        });

        $('#employee_date').change(function (){
            var dateId = $(this).val();
            console.log(dateId)
            var url = `${BASE_URL}/admin/bookings/getEmployeeTime/` + dateId;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const dateSelect = $('#employee_time');
                    dateSelect.empty();
                    (response.data || []).forEach((item, index) => {
                        const option2 = $('<option>', {
                            value: item.id,
                            text: item.time,
                            'data-status': item.status

                        });
                        if(index === 0) {
                            option2.prop('selected', true);
                        }
                        dateSelect.append(option2);
                    })
                },
                error: function(xhr, status, error) {
                    console.log('❌ خطا در دریافت پاسخ:');
                    console.log('   وضعیت:', status);
                    console.log('   خطا:', error);
                    console.log('   پاسخ سرور:', xhr.responseText);
                    console.log('   کد وضعیت:', xhr.status);
                }
            });

        });
        $('.js-example-basic-single').select2({
            minimumResultsForSearch: Infinity,
        });
        $('#service_id').trigger('change');
        $('#btn-search').on('click', function() {
            var $icon = $(this).find('i');
            var mobile = $('#search').val();

            if ($icon.hasClass('fa-')) {
                $('#search').val('');
                $('#phone_number').val('');
                $('#first_name').val('');
                $('#last_name').val('');
                $icon.removeClass('fa-xmark').addClass('fa-search');
                return;
            }

            if (!mobile) {
                clearUserFields();
                return;
            }
            $icon.removeClass('fa-search').addClass('fa-xmark');
            $.ajax({
                url: `${BASE_URL}/admin/bookings/searchUser/${mobile}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.data.length > 0) {
                        $('#first_name').val(data.data[0].first_name);
                        $('#last_name').val(data.data[0].last_name);
                        $('#phone_number').val(mobile);
                    } else {
                        clearUserFields();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error:', error);
                }
            });
        });
        $('#submitButton').click(function(e) {
            e.preventDefault();
            var selectedOption = $('#employee_time option:selected');
            var status = selectedOption.data('status');
            if (status == 1) {
                $('#reserveModal').modal('show');
            }
        });


    });
    function clearUserFields() {
        document.getElementById('phone_number').value = '';
        document.getElementById('first_name').value = '';
        document.getElementById('last_name').value = '';
    }
    function switchTab(index) {
        document.getElementById('circle0').classList.remove('active');
        document.getElementById('circle1').classList.remove('active');
        document.getElementById('circle' + index).classList.add('active');
        document.getElementById('content0').style.display = index === 0 ? 'block' : 'none';
        document.getElementById('content1').style.display = index === 1 ? 'block' : 'none';
    }
    switchTab(0);
</script>

