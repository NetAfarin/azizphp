
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
                   <select class="js-example-basic-single form-select w-100" name="service">
                       <?php foreach ($services as $ser): ?>
                           <option value="<?= $ser->id ?>">
                               <?=($lang == "fa") ? htmlspecialchars($ser->fa_title) : htmlspecialchars($ser->en_title) ?>
                           </option>
                       <?php endforeach; ?>
                   </select>
               </div>
                <h6 class="fw-bold mt-5"><?= __("choose_employee")?></h6>
                <div class="mt-4">
                    <label for="employee"><?= __('employee') ?><span class="bullet-color"> *</span></label>
                    <select class="js-example-basic-single form-select w-100" name="employee">
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee->id ?>">
                                <?= htmlspecialchars($employee->first_name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h6 class="fw-bold mt-5"><?= __("choose_time")?></h6>
                <div class="mt-4">
                    <div class="row">
                        <div class="col-6">
                            <label for="birthday-date"><?= __("birth_date") ?><span class="bullet-color"> *</span></label>
                            <div class="input-with-icon-left">
                                <input type="text" id="birth_date_picker" class="form-control" name="birth_date">
                                <i class="fa fa-calendar icon-color"></i>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="time"><?= __('time') ?><span class="bullet-color"> *</span></label>
                            <select class="js-example-basic-single form-select w-100" name="time">
                                <?php foreach ($employees as $employee): ?>
                                    <option value="<?= $employee->id ?>">
                                        <?= htmlspecialchars($employee->first_name) ?>
                                    </option>
                                <?php endforeach; ?>
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

