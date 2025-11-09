<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div class="mt-5">
    <div class="testi d-flex align-items-baseline">
        <label for="itemsInPage" class="px-2"><?= __("item_per_page") ?></label>
        <select class="js-example-basic-single sectionPagination" id="itemsInPage" name="state">
            <?php foreach ($allowedPerPage as $opt): ?>
                <option value="<?= $opt ?>" <?= $perPage === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
        </select>
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
                            <button class="btn btn-primary buttonSqaure">فیلتر</button>
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
                                <th><input class="form-check-input checkBox" type="checkbox" value="" id="allReserve">
                                    <label for="allReserve"><?= __("row") ?></label>
                                </th>
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
                                    <td>
                                        <input class="form-check-input checkBox" type="checkbox" value=""
                                               id="tableService<?= $count ?>">
                                        <label class="form-check-label" for="tableService<?= $count ?>">
                                            <?= htmlspecialchars($count) ?>
                                        </label>
                                    </td>
                                    <td><?= $service->service ?></td>
                                    <td><?= $service->employeeName ?>  <?= $service->employeeLastName ?></td>
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
                                                                    <span><?= __("employee") ?>: <?= $service->employeeName ?>  <?= $service->employeeLastName ?></span>
                                                                    <span><?= __("register_date") ?>: <span><?= toJalali($service->visitDate)['date'] ?></span></span>
                                                                    <span><?= __("register_time") ?>: <?= toJalali($service->visitDate)['time'] ?></span>
                                                                    <span><?= __("code") ?>: 123456</span>
                                                                    <span><?= __("submitDate") ?>:  <?= toJalali($service->registerDatetime)['date'] ?></span>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-outline-primary px-5 mt-4"
                                                                    style="margin-top: 35px;"
                                                                    data-bs-dismiss="modal"><?= __("close") ?></button>
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
            <div class="mt-4">
                <div class="d-flex justify-content-end">
                    <?php echo $renderPagination; ?>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-danger">پیدا نشد</div>
        <?php endif; ?>
    </div>

</div>


</body>

<script>
    $('#filterDate').on('click', function () {
        const fromDate = $('#birth_date_picker').val();
        const toDate = $('#birth_date_picker2').val();

        $.ajax({
            url: '/your/filter/url',
            method: 'GET',
            data: {from_date: fromDate, to_date: toDate},
            success: function (response) {
                console.log(response);
            },
            error: function () {
                alert('خطا در دریافت داده‌ها');
            }
        });
    });
    $(document).ready(function () {
        $("#birth_date_picker").persianDatepicker({
            format: 'YYYY-MM-DD',
            autoClose: true,
            initialValue: false,
            altField: '#birth_date',
            altFormat: 'YYYY-MM-DD',
            calendar: {
                persian: {
                    locale: 'fa'
                },
                gregorian: {
                    locale: 'en'
                }
            }
        });
        $("#birth_date_picker2").persianDatepicker({
            format: 'YYYY-MM-DD',
            autoClose: true,
            initialValue: false,
            altField: '#birth_date',
            altFormat: 'YYYY-MM-DD',
            calendar: {
                persian: {
                    locale: 'fa'
                },
                gregorian: {
                    locale: 'en'
                }
            }
        });
        $('#itemsInPage').on('change', function () {
            var perPage = $(this).val();
            var url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        });

        $('.js-example-basic-single').select2({
            minimumResultsForSearch: Infinity,
        });
    });
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || 'all';
    const links = document.querySelectorAll('#filterLinks a');

    links.forEach(link => {
        link.classList.remove('text-primary', 'text-dark');
        const href = new URL(link.href);
        if (href.searchParams.get('filter') === filter) {
            link.classList.add('text-primary');
        } else {
            link.classList.add('text-dark');
        }
    });
    var selectAllServices = document.getElementById("allReserve");
    selectAllServices.addEventListener("change", function () {
        var table = this.closest("table");
        var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
        checkboxes.forEach(cb => cb.checked = selectAllServices.checked);
    });
    const lang = document.documentElement.lang;
    const btn = document.querySelector('.btn-search');
    if (btn && lang === 'en') {
        btn.classList.add('ltr-input');
    }


</script>


</div>
</div>
