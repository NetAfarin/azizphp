
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
<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
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
        <div id="alertContainer" class="position-fixed top-0 start-50 translate-middle-x mt-3"></div>
        <div class="tab-content ">
            <div id="content0">
                <h6 class="fw-bold"><?= __("customer_information")?></h6>
                <form method="post" >
                    <?= csrf_field() ?>
                    <div class="input-group mt-5">
                        <input type="text" class="form-control border-end-0" name="search" id="search" placeholder="<?= __("search_customer_mobile") ?>" >
                            <button class="btn btn-search border-start-0" type="button" id="btn-search" >
                                <i class='fas fa-search'></i>
                            </button>
                    </div>
                    <div class="mt-5">
                    <label for="phone_number"><?= __('phone_number') ?><span class="bullet-color"> *</span></label>
                    <input type="text" class="form-control" name="phone_number" id="phone_number"
                           value="<?= $newPhonNumber ?? '' ?>" <?= !empty($newPhonNumber) ? 'readonly' : '' ?>>
                    <?php if (!empty($errors['phone_number'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['phone_number'][0]) ?></div>
                    <?php endif; ?>
                </div>
                <div class="row mt-5">
                    <div class="col-6">
                        <label for="first_name"><?= __('first_name') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" name="first_name" id="first_name" value="<?= $newFirstName ?? '' ?>" <?= !empty($newPhonNumber) ? 'readonly' : ''?> >
                        <?php if (!empty($errors['first_name'])): ?>
                            <div class="text-danger small"><?= htmlspecialchars($errors['first_name'][0]) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-6">
                        <label for="last_name"><?= __('last_name') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control" name="last_name" id="last_name" value="<?= $newLastName ?? '' ?>" <?= !empty($newPhonNumber) ? 'readonly' : '' ?>>
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
                                <option value="<?= $ser->id ?>"><?= ($lang=='fa') ?$ser->fa_title : $ser->en_title ?></option>
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
                    <div class="mt-4 rounded p-4" style="background: #F3F1F1">
                            <div class="col">
                                <h4><?= __("reserve_details") ?></h4>
                            </div>
                        <div class="col">
                            <?= __("customer") ?>:<span id="fullName"></span>
                        </div>
                        <div class="col mt-2">
                            <?= __("service") ?>: <span id="getService"></span>
                        </div>
                        <div class="col mt-2">
                            <?= __("employee") ?>: <span id="getEmployee"></span>
                        </div>
                        <div class="col mt-2">
                            <?= __("date_time") ?>:<span id="getEmployeeDate"></span>
                        </div>
                    </div>
                <button class="btn btn-primary mt-4 px-4" type="submit" id="submitButton"><?= __("final_reserve") ?></button>
                <button class="btn btn-outline-secondary mt-4 px-4" type="button"><?= __("clean") ?></button>
                    <div class="modal fade borderless-modal" id="reserveModal" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body custom-modal-body mt-4 mb-4">
                                    <h4 class="fw-bold">این ساعت قبلا رزرو شده است</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade borderless-modal" id="notFoundModal" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-body custom-modal-body mt-5 mb-5">
                                    <h5 class="fw-bold">مشتری با این مشخصات یافت نشد!</h5>

                                    <div class="d-flex gap-4 mt-5">
                                        <button type="button" class="btn btn-primary btn-modal" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                            ایجاد مشتری
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-modal" data-bs-dismiss="modal">انصراف</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="finalReserve" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" id="status_id">
                                    <div class="modal-body2">
                                        <h5 class="center fw-bold">رزرو با موفقیت انجام شد</h5>
                                        <div class="reserveDerailsModal rounded text-start">
                                            <div class="d-flex flex-column align-items-start gap-2 p-3">
                                                    <h6 class="fw-bold">خلاصه رزرو</h6>
                                                    مشتری:<span id="fullName"></span>
                                                    خدمات: <span id="getService"></span>
                                                    کارمند: <span id="getEmployee"></span>
                                                تاریخ / ساعت:<span id="getEmployeeDate"></span>
                                            </div>
                                        </div>
                                       <div class="col-12 d-flex justify-content-center">
                                           <button class="btn btn-outline-primary px-5 mt-4" style="margin-top: 35px;" type="button">
                                               <?= __("edit") ?>
                                           </button>
                                       </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <input type="hidden" name="id" id="service_id">
                                <div class="modal-body2">
                                    <span class="center"><?= __("add_user") ?></span>
                                    <input type="hidden" name="id" id="service_id">
                                    <div class="mt-4">
                                        <label for="phone_number_modal"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
                                        <input type="text" class="form-control" name="phone_number_modal" id="phone_number_modal">
                                    </div>
                                    <div class="mt-4">
                                        <label for="first_name_modal"><?= __("first_name") ?><span class="bullet-color"> *</span></label>
                                        <input type="text" class="form-control" name="first_name_modal" id="first_name_modal" >
                                    </div>
                                    <div class="mt-4">
                                        <label for="last_name_modal"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
                                        <input type="text" class="form-control" name="last_name_modal" id="last_name_modal" >
                                    </div>
                                    <div class="row g-2 px-5 mt-4">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-primary btn-modal w-100 py-2"><?= __("add_user") ?></button>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-secondary w-100 py-2" data-bs-dismiss="modal"><?= __("cancel") ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="content1" style="display:none;">این محتوای اولویت زمان است</div>
        </div>
    </div>

</div>
</div>
<script src="<?= asset('js/newBooking.js') ?>"></script>

