
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

    <div class="col mt-5">
        <div class="custom-part-with-border ">
          <div class="row">
              <div class="col-6">
                  <label for="fa_title"><?= __("title") ?> <span class="bullet-color"> *</span></label>
                  <input type="text" class="form-control" id="fa_title" name="fa_title">
              </div>
              <div class="col-6">
                  <label for="en_title"><?= __("english_title") ?> <span class="bullet-color"> *</span></label>
                  <input type="text" class="form-control" id="en_title" name="en_title">
              </div>
          </div>
            <div class="row mt-5">
                <div class="col-6">
                    <label class="form-check-label" for="serviceCategory"><?=__("add_as_service_category") ?></label>
                    <div class="form-check form-switch mt-4">
                        <input class="switch-input form-check-input" type="checkbox" role="switch"
                               id="serviceCategory" name="serviceCategory">
                    </div>
                </div>
                <div class="col-6">
                    <label for="category" class="form-label mb-0"><?= __('select_category') ?>:</label>
                    <select class="js-example-basic-single w-100"  id="category" name="category" >
                        <?php foreach ($services as $ser): ?>
                            <option value="<?= $ser->id ?>">
                                <?=($lang == "fa") ? htmlspecialchars($ser->fa_title) : htmlspecialchars($ser->en_title) ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
          <div class="d-flex justify-content-end">
              <button class="btn btn-primary mt-5 px-5" type="submit"><?= __("add")?></button>
          </div>

        </div>
    </div>
</form>
<div class="col mt-5">
    <div class="row mt-5">
        <div class="col-6 testi">
            <label for="paginationOption"><?= __("item_per_page") ?></label>
            <select class="js-example-basic-single sectionPagination" id="itemsInPage" name="state">
                <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
                <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
            </select>
        </div>
    </div>
    <div class="row mt-4">
        <div class="custom-part-with-border">
            <div class="d-flex  justify-content-between gap-sm-2">
                <div class="d-flex">
                    <select class="js-example-basic-single " name="state" id="xx">
                        <option><?= __("group_work") ?></option>
                        <option><?= __("group_work") ?></option>
                        <option><?= __("group_work") ?></option>
                    </select>

                    <button class="btn btn-primary mx-1" style="width: 50px;height: 50px;"><?= __("execution") ?></button>
                </div>
                <form method="get" class="d-flex align-items-center gap-2 mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control border-end-0 " name="search" id="search"
                               value="<?= htmlspecialchars($search) ?>" placeholder="<?= __("search") ?>">
                        <button
                                class="btn btn-search border-start-0"
                                type="submit" id="btn-search">
                            <i class="fa fa-search"></i>
                        </button>

                    </div>
                    <?php if (!empty($per_page)): ?>
                        <input type="hidden" name="per_page" value="<?= $per_page ?>">
                    <?php endif; ?>
                    <?php if (!empty($search)): ?>
                        <a href="?page=1&per_page=<?= $per_page ?>" class="btn btn-danger btn-delete">✖ <?= __('clear') ?></a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="d-flex flex-wrap justify-content-between mt-5">
                <div class="d-flex" id="filterLinks">
                    <a href="?filter=all" class="text-decoration-none"><?= __("all") ?> (<?= $all ?>)</a>
                    <div class="vertical-separator"></div>
                    <a href="?filter=categories" class="text-decoration-none "><?= __("category") ?> (<?= $categorySize ?>)</a>
                    <div class="vertical-separator"></div>
                    <a href="?filter=services" class="text-decoration-none "><?= __("services") ?> (<?= $serviceSize ?>)</a>
                </div>
                <div><?= $items ?> <?=  __("item") ?></div>
            </div>

        </div>
        <?php if(!empty($allServices)): ?>
        <div class="row mt-5">
                <div class="table-wrapper">
                    <table class="table custom-table">
                    <thead class="table-primary ">
                    <tr>
                        <th> <input class="form-check-input checkBox" type="checkbox" value="" id="allServices">
                            <label for="allServices"><?= __("row") ?></label>
                        </th>
                        <th class="text-center" >
                            <a href="<?= $sortTitleUrl ?>" class="text-decoration-none text-white">
                                <?= __('title')?>
                                <i class="fas fa-sort-amount-down-alt mx-1"></i>
                            </a>
                        </th>
                        <th >
                            <a href="<?= $sortCategoryUrl ?>" class="text-decoration-none text-white">
                                <?= __('category')?>
                                <i class="fas fa-sort-amount-down-alt mx-1"></i>
                            </a>
                        </th>
                        <th>
                            <a href="<?= $sortServiceCountUrl ?>" class="text-decoration-none text-white">
                                <?= __('sub_category_count')?>
                                <i class="fas fa-sort-amount-down-alt mx-1"></i>
                            </a>
                        </th>
                        <th><?= __("actions") ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $startNumber = (($pagination['current_page'] - 1) * $per_page) + 1;
                    $count = $startNumber;
                    ?>
                    <?php foreach ($allServices as $service): ?>
                        <tr>


                            <td>
                                <input class="form-check-input checkBox" type="checkbox" value="" id="tableService<?= $count?>">
                                <label class="form-check-label" for="tableService<?= $count?>">
                                    <?=  htmlspecialchars($count)?>
                                </label>
                            </td>
                            <td>
                                <?php if ($service->parent_id == 0): ?>
                                    <div class="d-flex justify-content-between">
                                        <span class="badge category-bg-light-green" style="opacity: 0">دسته بندی</span>
                                        <?=  htmlspecialchars($service->title);  ?>
                                        <span class="badge category-bg-light-green"><?= __("category") ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex justify-content-center"><?=  htmlspecialchars($service->title)?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($service->parent_id !=0 ):?>
                                    <?= htmlspecialchars( $service->parent_title) ?>
                                <?php else:?>
                                    -
                                <?php endif;?>
                            </td>
                            <?php if ($service->parent_id == 0): ?>
                                <td>
                                    <?= $service->childCount ?>
                                </td>
                            <?php else: ?>
                                <td >-</td>
                            <?php endif; ?>

                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                <div class="dropdown">
                                    <button class="btn btn-active activities-icon"
                                            id="navbarDropdownMenuLink"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="fa fa-ellipsis-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end p-0 action-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <li><a class="dropdown-item text-start" href="<?= BASE_URL ?>/admin/services/category/edit/<?= $service->id ?>" data-bs-toggle="modal" data-bs-target="#editModal">ویرایش</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-start">حذف</a></li>
                                    </ul>
                                </div>

                                    <!--                                    <a href="--><?php //= BASE_URL ?><!--/admin/services/category/edit/--><?php //= $service->id ?><!--" class="btn btn-active activities-icon">-->
<!--                                        <span class="fa fa-edit"></span>-->
<!--                                    </a>-->
<!--                                <form action="--><?php //= BASE_URL ?><!--/admin/services/category/delete/--><?php //= $service->id ?><!--" method="post" class="d-inline"-->
<!--                                      onsubmit="return confirm('--><?php //= __('confirm_delete_category') ?>
                                   <?php //= csrf_field() ?>
<!--                                    <button class="btn btn-active activities-icon"><span class="fa fa-trash"></span></button>-->
<!--                                </form>-->
<!--                                </div>-->
<!--                                <div class="d-flex justify-content-center gap-1">-->
<!--                                    <a href="--><?php //= BASE_URL ?><!--/admin/services/delete/--><?php //= $service->id ?><!--t" class="btn btn btn-active activities-icon">-->
<!--                                        <span class="fa fa-edit"></span>-->
<!--                                    </a>-->
<!---->
                            </td>

                        </tr>
                        <?php $count++ ?>
                    <?php endforeach;?>
                    <div class="modal fade borderless-modal" id="editModal" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content ">
                                <div class="modal-body custom-modal-body">
                                    <div class="row">
                                        <div class="col-12 mt-4">
                                            <label for="fa_title"><?= __("title") ?> <span class="bullet-color"> *</span></label>
                                            <input type="text" class="form-control" id="fa_title" name="fa_title" value="<?= htmlspecialchars(old('fa_title', $services->fa_title ?? '')) ?>">
                                        </div>
                                        <div class="col-12 mt-4">
                                            <label for="en_title"><?= __("english_title") ?> <span class="bullet-color"> *</span></label>
                                            <input type="text" class="form-control" id="en_title" name="en_title" value="<?= htmlspecialchars(old('en_title', $services->en_title ?? '')) ?>">
                                        </div>
                                        <div class="col-12 mt-4">
                                            <label class="form-check-label" for="serviceCategory"><?=__("add_as_service_category") ?></label>
                                            <div class="form-check form-switch ">
                                                <input class="switch-input form-check-input" type="checkbox" role="switch"
                                                       id="serviceCategory" name="serviceCategory">
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label for="category" class="form-label mb-0"><?= __('select_category') ?>:</label>
                                            <select class="js-example-basic-single2 w-100"  id="category" name="category" >
                                                <?php foreach ($services as $ser): ?>
                                                    <option value="<?= $ser->id ?>">
                                                        <?=($lang == "fa") ? htmlspecialchars($ser->fa_title) : htmlspecialchars($ser->en_title) ?>
                                                    </option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-4">
                                        <button type="button" class="btn btn-primary btn-modal">بله</button>
                                        <button type="button" class="btn btn-outline-secondary btn-modal" data-bs-dismiss="modal">خیر</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    </tbody>
                </table>
            </div>


        </div>
        <div class="row mt-4">
            <?php if (!empty($pagination) && $pagination['last_page'] > 1): ?>
                <nav aria-label="Page navigation " class="p-0">
                    <ul class="pagination justify-content-end">
                        <li class="page-item <?= $pagination['current_page'] == 1 ?>">
                            <a class="page-link icon-pagination"
                               href="?page=1&per_page=<?= $per_page ?>"
                               aria-label="First">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                            <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&per_page=<?= $per_page ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($sortBy) ? '&sortby=' . urlencode($sortBy).('&sortorder='.$sortOrder) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $pagination['current_page'] == $pagination['last_page'] ?>">
                            <a class="page-link icon-pagination"
                               href="?page=<?= $pagination['last_page'] ?>&per_page=<?= $per_page ?>"
                               aria-label="Last">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-danger">پیدا نشد</div>
        <?php endif; ?>
    </div>
</div>

<script>
    const lang = document.documentElement.lang; // "en" یا "fa"
    const btn = document.querySelector('.btn-search');

    if (lang === 'en') {
        btn.classList.add('ltr-input');
    }

    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter') || 'all';
    const links = document.querySelectorAll('#filterLinks a');

    links.forEach(link => {
        link.classList.remove('text-primary');
        link.classList.add('text-dark');
        const href = new URL(link.href);
        if(href.searchParams.get('filter') === filter) {
            link.classList.remove('text-dark');
            link.classList.add('text-primary');
        }
    });
    var selectAllServices = document.getElementById("allServices");
    selectAllServices.addEventListener("change", function () {
        var table = this.closest("table");
        var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
        checkboxes.forEach(cb => cb.checked = selectAllServices.checked);
    });
    function toggleCategoryDisplay() {
        const switchInput = document.getElementById('serviceCategory');
        const categoryElement = document.getElementById('category');

        if (switchInput.checked) {
            categoryElement.style.display = 'block';
        } else {
            categoryElement.style.display = 'none';
        }
    }

    const switchInput = document.getElementById('serviceCategory');
    const categoryElement = document.getElementById('category');

    switchInput.addEventListener('click', function() {
        if (this.checked) {
            categoryElement.disabled = true;
            categoryElement.classList.add('selected');
        } else {
            categoryElement.disabled = false;
            categoryElement.classList.remove('selected');
        }
    });
    $('#itemsInPage').on('change', function () {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });

</script>


</div>
</div>
</body>
<script src="<?= asset('/js/register-user.js') ?>"></script>
