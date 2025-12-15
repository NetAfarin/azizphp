<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div class="mt-5">
    <div class="d-flex  align-items-baseline gap-2 mt-5">
        <label for="itemsInPage"><?= __("item_per_page") ?></label>
        <div>
            <select class="perPageSelect sectionPagination" id="itemsInPage" name="state">
                <?php foreach ($allowedPerPage as $opt): ?>
                    <option value="<?= $opt ?>" <?= $perPage === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="custom-part-with-border mt-4">
        <div class="d-flex  justify-content-between gap-sm-2">
            <form method="get" action="">
                <div class="d-flex gap-2">
                    <div class="col">
                        <div class="input-group">
                            <input type="text" id="birth_date_picker" class="form-control border-end-0"
                                   name="from_date" placeholder="<?= __("from_date") ?>"
                                   value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
                            <button class="btn btn-search border-start-0" type="button">
                                <i class='fas fa-calendar'></i>
                            </button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <input type="text" id="birth_date_picker2" class="form-control border-end-0"
                                   name="to_date" placeholder="<?= __("to_date") ?>"
                                   value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
                            <button class="btn btn-search border-start-0" type="button">
                                <i class='fas fa-calendar'></i>
                            </button>
                        </div>
                    </div>
                    <?php if ((empty($fromDate)) && empty($toDate)): ?>
                        <div class="col">
                            <button class="btn btn-primary buttonSqaure"><?= __("filter")?></button>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-primary buttonSqaure" type="button">
                            <a href="?page=1&per_page=<?= $perPage ?>" class="center text-decoration-none"><i
                                        class='fas fa-xmark text-white'></i></a>
                        </button>
                    <?php endif; ?>
                </div>

            </form>
            <form method="get" class="d-flex align-items-center gap-2 mb-2">
                <div class="input-group">
                    <input type="text" class="form-control border-end-0" name="search" id="search"
                           value="<?= htmlspecialchars($search) ?>" placeholder="<?= __("search") ?>">
                    <?php if (empty($search)): ?>
                        <button class="btn btn-search border-start-0" type="submit" id="btn-search">
                            <i class='fas fa-search'></i>
                        </button>
                    <?php else: ?>
                        <?php if (!empty($search)): ?>
                            <button class="btn btn-search border-start-0" type="button" id="btn-delete">
                            <a href="?page=1&per_page=<?= $perPage ?>" class="center text-decoration-none"><i
                                        class='fas fa-xmark text-primary'></i></a>
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <div class="d-flex flex-wrap justify-content-between mt-5">
            <div class="d-flex" id="filterLinks">
                <?php
                $originalFromDate = $_GET['from_date'] ?? '';
                $originalToDate = $_GET['to_date'] ?? '';
                ?>
                <a href="?filter=all<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none"><?= __("all") ?> (<?= $allVisits ?>)</a>
                <div class="vertical-separator"></div>
                <a href="?filter=reserved<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none">
                    <?= __("reserved") ?> (<?= $visitsReserved ?>)
                </a>
                <div class="vertical-separator"></div>
                <a href="?filter=done<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none "><?= __("done") ?> (<?= $allDoneVisitsSize ?>)</a>
                <div class="vertical-separator"></div>
                <a href="?filter=cancelled<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none">
                    <?= __("cancelled") ?> (<?= $allCancelledVisitsSize ?>)
                </a>
                <div class="vertical-separator"></div>
                <a href="?filter=verify<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none">
                    <?= __("verify") ?> (<?= $allVerifySize ?>)
                </a>
                  <div class="vertical-separator"></div>
                <a href="?filter=in_progress<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none">
                    <?= __("in_progress") ?> (<?= $allInProgressVisits ?>)
                </a>
                <div class="vertical-separator"></div>
                <a href="?filter=no_show<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none">
                    <?= __("no_show") ?> (<?= $allNoShowSize ?>)
                </a>
                <div class="vertical-separator"></div>
                <a href="?filter=rescheduled<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?><?= ((!empty($originalFromDate)) && (!empty($originalToDate))) ? "&from_date=" . urlencode($originalFromDate) . "&" . "to_date=" . urlencode($originalToDate) : "" ?>"
                   class="text-decoration-none">
                    <?= __("rescheduled") ?> (<?= $allRescheduledSize ?>)
                </a>
            </div>
            <?php if (isset($_GET['search'])): ?>
                <div><?php printf(__("item"), $searchItems) ?></div>
            <?php endif; ?>
        </div>
    </div>
            <div class="mt-5">
                <?php if (!empty($allReserve)): ?>
                <div class="table-wrapper">
                    <div class="table-responsive ">
                        <table class="table custom-table">
                            <thead class="table-primary ">
                            <tr>
                                <th><label for="allReserve"><?= __("row") ?></label></th>
                                <th class="text-center">
                                    <a href="<?= $sortServiceUrl ?>"
                                       class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                        <?= __('service') ?>
                                        <?php if ($sortBy === 'service'): ?>
                                            <?php if ($sortOrder === 'asc'): ?>
                                                <i class="fa-solid fa-caret-up mx-1"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-caret-down mx-1"></i>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-caret-down mx-1"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?= $sortEmployeeUrl ?>"
                                       class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                        <?= __('employee') ?>
                                        <?php if ($sortBy === 'employee'): ?>
                                            <?php if ($sortOrder === 'asc'): ?>
                                                <i class="fa-solid fa-caret-up mx-1"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-caret-down mx-1"></i>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-caret-down mx-1"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>
                                    <a href=""
                                       class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                        <?= __("code") ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?= $sortDateUrl ?>"
                                       class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                        <?= __('visit_date') ?>
                                        <?php if ($sortBy === 'date'): ?>
                                            <?php if ($sortOrder === 'asc'): ?>
                                                <i class="fa-solid fa-caret-up mx-1"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-caret-down mx-1"></i>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-caret-down mx-1"></i>
                                        <?php endif; ?>
                                    </a>
                                </th>
                                <th>
                                    <a href=""
                                       class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                        <?= __('visit_time') ?>
                                    </a>
                                </th>
                                <th>
                                    <a href=""
                                       class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                        <?= __('status') ?>
                                    </a>
                                </th>
                                <th><?= __("actions") ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $startNumber = (($pagination['current_page'] - 1) * $perPage) + 1;
                            $count = $startNumber;
                            ?>
                            <?php foreach ($allReserve as $service): ?>
                                <tr>
                                    <td><?= htmlspecialchars($count) ?></td>
                                    <td><?= $service->service ?></td>
                                    <td><?= $service->employeeFirstName ?>  <?= $service->employeeLastName ?></td>
                                    <td>123456</td>
                                    <td><?= toJalali($service->visitDate)['date']; ?></td>
                                    <td><?= toJalali($service->visitDate)['time']; ?></td>
                                    <td class="<?php
                                    if ($service->visitStatusId == 5) echo 'text-dark';
                                    elseif ($service->visitStatusId == 1) echo 'text-success';
                                    elseif ($service->visitStatusId == 3) echo 'text-danger';
                                    ?>">
                                        <?= htmlspecialchars($service->visitStatus) ?>
                                    </td>


                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <div class="dropdown">
                                                <button class="btn btn-active activities-icon"
                                                        id="navbarDropdownMenuLink"
                                                        type="button"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    <i class="fa fa-ellipsis-vertical fs-4"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end p-0 action-menu"
                                                    aria-labelledby="navbarDropdownMenuLink">

                                                    <li>
                                                        <a class="dropdown-item text-start editServiceBtn"
                                                           type="button"
                                                           data-bs-target="#showDetails"
                                                           data-bs-toggle="modal"
                                                           data-id="<?= $service->id ?>">
                                                            <?= __("details") ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="modal fade" id="showDetails" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <input type="hidden" name="id" id="service_id">
                                                        <div class="modal-body2">
                                                            <h5 class="center fw-bold"><?= __("reserve_details") ?></h5>
                                                            <div class="reserveDerailsModal rounded text-start">
                                                                <div class="d-flex flex-column gap-3 p-3">
                                                                    <span><?= __("customer") ?>: <?= $service->customerName ?></span>
                                                                    <span><?= __("service") ?>: <?= $service->service ?></span>
                                                                    <span><?= __("employee") ?>: <?= $service->employeeFirstName ?>  <?= $service->employeeLastName ?></span>
                                                                    <span><?= __("register_date") ?>: <span><?= toJalali($service->visitDate)['date'] ?></span></span>
                                                                    <span><?= __("register_time") ?>: <?= toJalali($service->visitDate)['time'] ?></span>
                                                                    <span><?= __("code") ?>: 123456</span>
                                                                    <span><?= __("submitDate") ?>:  <?= toJalali($service->registerDatetime)['date'] ?></span>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-outline-primary px-5 mt-4"
                                                                    style="margin-top: 35px;"
                                                                    data-bs-dismiss="modal"><?= __("close2") ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                             </tr>
                                <?php $count++ ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    <?php if ($page > 1):?>
        <div class="mt-3">
            <div class="d-flex mb-0 justify-content-end align-items-center">
                <?php echo $renderPagination; ?>
            </div>
        </div>
    <?php endif;?>
        <?php else: ?>
            <div class="alert alert-danger"><?= __("not_found") ?></div>
        <?php endif; ?>
    </div>

</div>
</div>
</div>
<script src="<?= asset('js/booking/manage-booking.js') ?>"></script>

