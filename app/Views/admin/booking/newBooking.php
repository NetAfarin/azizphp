
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
                <form method="get" class="d-flex align-items-center gap-2 mb-2">
<!--                    <input type="hidden" name="filter" value="--><?php //= htmlspecialchars($filter) ?><!--">-->
                    <div class="input-group mt-5">
                        <input type="text" class="form-control border-end-0" name="search" id="search"
                               value="<?= htmlspecialchars($search) ?>" placeholder="<?= __("search_customer_mobile") ?>">
                        <?php if(empty($search)):?>
                            <button class="btn btn-search border-start-0" type="submit" id="btn-search">
                                <i class='fas fa-search'></i>
                            </button>
                        <?php else:?>
                            <?php if (!empty($search)): ?><button class="btn btn-search border-start-0" type="button" id="btn-delete">
                                <a href="?page=1&per_page=<?= $per_page ?>" class="center text-decoration-none"><i class='fas fa-xmark text-primary'></i></a>
                                </button>
                            <?php endif;?>
                        <?php endif; ?>
                    </div>
                </form>
                <div class="mt-5">
                    <label for="phone_number"><?= __('phone_number') ?><span class="bullet-color"> *</span></label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number"
                           value="<?= old('phone_number') ?>">
                    <?php if (!empty($errors['phone_number'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['phone_number'][0]) ?></div>
                    <?php endif; ?>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="first_name"><?= __('first_name') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" name="first_name" id="first_name">
                    </div>
                    <div class="col-6">
                        <label for="last_name"><?= __('last_name') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" name="last_name" id="last_name">
                    </div>
                </div>
                <h6 class="fw-bold mt-5"><?= __("choose_service")?></h6>
                <div class="mt-4">
                    <label for="service"><?= __('service') ?><span class="bullet-color"> *</span></label>
                    <form id="service_form" method="post">
                        <?= csrf_field() ?>
                        <select  class="js-example-basic-single form-select w-100" id="service_id" name="service">
                            <?php foreach ($services as $ser): ?>
                                <option value="<?= $ser->id ?>"><?= $ser->fa_title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <h6 class="fw-bold mt-5"><?= __("choose_employee")?></h6>
                <div class="mt-4">
                    <label for="employee"><?= __('employee') ?><span class="bullet-color"> *</span></label>
                    <form id="employee_form" method="post">
                        <?= csrf_field() ?>
                    <select class="js-example-basic-single form-select w-100" name="employee" id="employee_select">
                    </select>
                    </form>
                </div>

                <h6 class="fw-bold mt-5"><?= __("choose_time")?></h6>
                <div class="mt-4">
                    <div class="row">
                        <div class="col-6">
                            <label for="birthday-employee_date"><?= __("date") ?><span class="bullet-color"> *</span></label>
                            <div class="input-with-icon-left">
                                <select class="js-example-basic-single form-select w-100" name="time"  id="employee_date">
                                </select>
<!--                                <input type="text" id="employee_date" class="form-control" name="employee_date">-->
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
            </div>
            <div id="content1" style="display:none;">این محتوای اولویت زمان است</div>
        </div>
    </div>

</div>
</div>
<script>

    function loadEmployees(serviceId) {
        var formData = new FormData(document.getElementById('service_form'));

        fetch(`${BASE_URL}/admin/bookings/get/${serviceId}`, {
            method: 'POST',
            body: formData
        })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                const select = document.getElementById('employee_select');
                select.innerHTML = '';
                data.data.forEach((item, index) => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.first_name;
                    select.appendChild(option);
                    if(index === 0) {
                        option.selected = true;
                    }
                });

                const firstOptionVal = $(select).find('option:first').val();
                if(firstOptionVal) {
                    $(select).val(firstOptionVal).trigger('change'); // trigger change واقعی
                }
            })
            .catch(error => {
                console.error('❌ Error:', error);
                alert('خطا در بروزرسانی سرویس');
            });
    }
    $('#service_id').change(function() {
        loadEmployees(this.value);
    });
    $('#employee_select').change(function() {
        var selectedEmployeeId = $(this).val();
        console.log("Selected Employee ID:", selectedEmployeeId);
        var formData = new FormData(document.getElementById('employee_form'));
        formData.append('employee_id', selectedEmployeeId);
        fetch(`${BASE_URL}/admin/bookings/getEmployeeTime/${selectedEmployeeId}`, {
            method: 'POST',
            body: formData
        })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                console.log(data)
                const select = document.getElementById('employee_time');
                const date = document.getElementById('employee_date');
                select.innerHTML = '';
                date.innerHTML = '';
                data.data.forEach((item, index) => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.start_time.substring(0,5)} الی ${item.end_time.substring(0,5)}`;
                    select.appendChild(option);
                    if(index === 0) {
                        option.selected = true;
                    }

                    const option2 = document.createElement('option');
                    option2.value = item.id;
                    option2.textContent = `${item.start_time.substring(0,5)} الی ${item.end_time.substring(0,5)}`;
                    date.appendChild(option2);
                    if(index === 0) {
                        option2.selected = true;
                    }

                });


                // const firstOptionVal = $(select).find('option:first').val();
                // if(firstOptionVal) {
                //     $(select).val(firstOptionVal).trigger('change'); // trigger change واقعی
                // }
            })
            .catch(error => {
                console.error('❌ Error:', error);
                alert('خطا در بروزرسانی سبریبرویس');
            });
    });

    $(document).ready(function() {
        const defaultServiceId = $('#service_id').val();
        if(defaultServiceId) {
            loadEmployees(defaultServiceId);
        }
    });

    $(document).ready(function () {

        $('.js-example-basic-single').select2({
            minimumResultsForSearch: Infinity,
        });
    });



    function switchTab(index) {
        document.getElementById('circle0').classList.remove('active');
        document.getElementById('circle1').classList.remove('active');
        document.getElementById('circle' + index).classList.add('active');
        document.getElementById('content0').style.display = index === 0 ? 'block' : 'none';
        document.getElementById('content1').style.display = index === 1 ? 'block' : 'none';
    }
    switchTab(0);
</script>

