
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
<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div class="col-12 mt-5 bg-white shadow-md rounded p-3">
    <div class="d-flex justify-content-around align-items-center">
        <div class="tab-item text-center d-flex" onclick="switchTab(0)">
            <div class="tab-circle" id="circle0"></div>
            <div class="px-2"><?= __("employee_priority") ?></div>
        </div>
        <div class="tab-item text-center d-flex" onclick="switchTab(1)">
            <div class="tab-circle" id="circle1"></div>
            <div class="px-2"><?= __("time_priority") ?></div>
        </div>
    </div>
</div>
    <div class="col-12 mt-5 bg-white shadow-md rounded p-4">
        <div class="tab-content ">
            <div id="content0">
                <h6 class="fw-bold"><?= __("customer_information")?></h6>
                <form method="post" class="search-block">
                    <?= csrf_field() ?>
                    <div class="input-group mt-5 search-input-group">
                        <input type="text" class="form-control border-end-0 search-input" data-target="#content0" name="search" placeholder="<?= __("search_customer_mobile") ?>">
                        <button class="btn btn-search border-start-0 btn-search-btn" type="button">
                            <i class='fas fa-search'></i>
                        </button>
                    </div>

                    <div class="mt-5">
                        <label><?= __('phone_number') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control phone_number" name="phone_number" readonly>
                    </div>

                    <div class="row mt-5">
                        <div class="col-6">
                            <label><?= __('first_name') ?><span class="bullet-color"> *</span></label>
                            <input type="text" class="form-control first_name" name="first_name" readonly>
                            <input type="hidden" class="first_name_hidden" name="first_name_hidden">
                        </div>
                        <div class="col-6">
                            <label><?= __('last_name') ?><span class="bullet-color"> *</span></label>
                            <input type="text" class="form-control last_name" name="last_name" readonly>
                            <input type="hidden" class="last_name_hidden" name="last_name_hidden">
                        </div>
                    </div>

                    <h6 class="fw-bold mt-5"><?= __("choose_service")?></h6>
                    <div class="mt-4">
                        <label><?= __('service') ?><span class="bullet-color"> *</span></label>
                        <select class="js-example-basic-single form-select w-100 service_select" name="service">
                            <?php foreach ($services as $ser): ?>
                                <option value="<?= $ser->id ?>"><?= ($lang=='fa') ?$ser->fa_title : $ser->en_title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <h6 class="fw-bold mt-5"><?= __("choose_employee")?></h6>
                    <div class="mt-4">
                        <label><?= __('employee') ?><span class="bullet-color"> *</span></label>
                        <select class="js-example-basic-single form-select w-100 employee_select" name="employee"></select>

                    </div>

                    <h6 class="fw-bold mt-5"><?= __("choose_time")?></h6>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-6">
                                <label><?= __("date") ?><span class="bullet-color"> *</span></label>
                                <div class="input-with-icon-left">
                                    <select class="js-example-basic-single form-select w-100 employee_date" name="date"></select>
                                    <i class="fa fa-calendar icon-color"></i>
                                </div>

                            </div>
                            <div class="col-6">
                                <label><?= __('time') ?><span class="bullet-color"> *</span></label>
                                <select class="js-example-basic-single form-select w-100 employee_time" name="time"></select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 rounded p-4" style="background: #F3F1F1">
                        <div class="col">
                            <h4><?= __("reserve_details") ?></h4>
                        </div>
                        <div class="col"><?= __("customer") ?>: <span class="fullName"></span></div>
                        <div class="col mt-2"><?= __("service") ?>: <span class="getService"></span></div>
                        <div class="col mt-2"><?= __("employee") ?>: <span class="getEmployee"></span></div>
                        <div class="col mt-2"><?= __("date_time") ?>: <span class="getEmployeeDate"></span></div>
                    </div>

                    <button class="btn btn-primary mt-4 px-4 submitButton" type="submit"><?= __("final_reserve") ?></button>
                    <button class="btn btn-outline-secondary mt-4 px-4 cleanButton" type="button"><?= __("clean") ?></button>
                </form>
            </div>
            <div id="content1" style="display:none;">
                <h6 class="fw-bold"><?= __("customer_information")?></h6>
                <form method="post" class="block-with-time_priority">
                    <?= csrf_field() ?>
                    <div class="input-group mt-5 search-input-group">
                        <input type="text" class="form-control border-end-0 search-input" data-target="#content0" name="search" placeholder="<?= __("search_customer_mobile") ?>">
                        <button class="btn btn-search border-start-0 btn-search-btn" type="button">
                            <i class='fas fa-search'></i>
                        </button>
                    </div>

                    <div class="mt-5">
                        <label><?= __('phone_number') ?><span class="bullet-color"> *</span></label>
                        <input type="text" class="form-control phone_number" name="phone_number" readonly>
                    </div>

                    <div class="row mt-5">
                        <div class="col-6">
                            <label><?= __('first_name') ?><span class="bullet-color"> *</span></label>
                            <input type="text" class="form-control first_name" name="first_name" readonly>
                            <input type="hidden" class="first_name_hidden" name="first_name_hidden">
                        </div>
                        <div class="col-6">
                            <label><?= __('last_name') ?><span class="bullet-color"> *</span></label>
                            <input type="text" class="form-control last_name" name="last_name" readonly>
                            <input type="hidden" class="last_name_hidden" name="last_name_hidden">
                        </div>
                    </div>

                    <h6 class="fw-bold mt-5"><?= __("choose_service")?></h6>
                    <div class="mt-4">
                        <label><?= __('service') ?><span class="bullet-color"> *</span></label>
                        <select class="js-example-basic-single form-select w-100 service_select" name="service">
                            <?php foreach ($services as $ser): ?>
                                <option value="<?= $ser->id ?>"><?= ($lang=='fa') ?$ser->fa_title : $ser->en_title ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>



                    <h6 class="fw-bold mt-5"><?= __("choose_time")?></h6>
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-6">
                                <label><?= __("date") ?><span class="bullet-color"> *</span></label>
                                <div class="input-with-icon-left">
                                    <select class="js-example-basic-single form-select w-100 employee_date2" name="date"></select>
                                    <i class="fa fa-calendar icon-color"></i>
                                </div>                            </div>
                            <div class="col-6">
                                <label><?= __('time') ?><span class="bullet-color"> *</span></label>
                                <select class="js-example-basic-single form-select w-100 employee_time2" name="time"></select>
                            </div>
                        </div>
                    </div>
                    <h6 class="fw-bold mt-5"><?= __("choose_employee")?></h6>
                    <div class="mt-4">
                        <label><?= __('employee') ?><span class="bullet-color"> *</span></label>
                        <select class="js-example-basic-single form-select w-100 employee_select2" name="employee"></select>
                    </div>
                    <div class="mt-4 rounded p-4" style="background: #F3F1F1">
                        <div class="col">
                            <h4><?= __("reserve_details") ?></h4>
                        </div>
                        <div class="col"><?= __("customer") ?>: <span class="fullName"></span></div>
                        <div class="col mt-2"><?= __("service") ?>: <span class="getService"></span></div>
                        <div class="col mt-2"><?= __("employee") ?>: <span class="getEmployee"></span></div>
                        <div class="col mt-2"><?= __("date_time") ?>: <span class="getEmployeeDate"></span></div>
                    </div>

                    <button class="btn btn-primary mt-4 px-4 submitButton" type="submit"><?= __("final_reserve") ?></button>
                    <button class="btn btn-outline-secondary mt-4 px-4 cleanButton" type="button"><?= __("clean") ?></button>
                </form>
            </div>
        </div>

    </div>
<div class="modal fade" id="finalReserve" tabindex="-1"  data-show-modal="<?= $showReservationModal ? 'true' : 'false' ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body2">
                <h5 class="center fw-bold"><?= __("booking_success") ?></h5>
                <div class="reserveDerailsModal rounded text-start">
                    <div class="d-flex flex-column align-items-start gap-2 p-3">
                        <h6 class="fw-bold"><?= __("booking_details") ?></h6>
                        <div> <?= __("customer") ?>: <span><?= $reservationData['fullName'] ?? '' ?></span></div>
                        <div> <?= __("service") ?>: <span ><?= $reservationData['service'] ?? '' ?></span></div>
                        <div> <?= __("employee") ?>: <span id="getEmployee"><?= $reservationData['employee'] ?? '' ?></span></div>
                        <div>  <?= __("date_time") ?>: <span id="getEmployeeDate"><?= (toJalali($reservationData['dateTime'])['date'] ?? '') ." / ". toJalali($reservationData['dateTime'])['time'] ?? '' ?></span></div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <button class="btn btn-outline-primary px-5 mt-4"
                            type="button"
                            data-bs-dismiss="modal">
                        <?= __("close2") ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade borderless-modal" id="notFoundModal" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body custom-modal-body mt-5 mb-5">
                <h5 class="fw-bold"><?= __("customer_not_found") ?></h5>
                <div class="d-flex gap-4 mt-5">
                    <button type="button" class="btn btn-primary show-modal btn-modal"  data-bs-target="#createUserModal">
                        <?= __("create_user") ?>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-modal" data-bs-dismiss="modal"><?= __("cancel") ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body2">
                <span class="center"><?= __("add_user") ?></span>
                <div class="mt-4">
                    <label for="phone_number_modal"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
                    <input type="hidden" name="phone_number_hidden"  id="phone_number_hidden">
                    <input type="text" class="form-control" name="phone_number_modal" id="phone_number_modal">
                </div>
                <div class="mt-4">
                    <label for="first_name_modal"><?= __("first_name") ?><span class="bullet-color"> *</span></label>
                    <input type="text" class="form-control" name="first_name_modal" id="first_name_modal" >
                    <?php if (!empty($errors['first_name_modal'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['first_name_modal'][0]) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mt-4">
                    <label for="last_name_modal"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
                    <input type="text" class="form-control" name="last_name_modal" id="last_name_modal" >
                    <?php if (!empty($errors['last_name_modal'])): ?>
                        <div class="text-danger small"><?= htmlspecialchars($errors['last_name_modal'][0]) ?></div>
                    <?php endif; ?>
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
</div>
</div>
<script src="<?= asset('js/booking/newBooking.js') ?>"></script>

