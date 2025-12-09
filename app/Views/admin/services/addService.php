
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
    <div class="d-flex  align-items-baseline gap-2">
        <label for="itemsInPage"><?= __("item_per_page") ?></label>
        <div>
            <select class="js-example-basic-single sectionPagination" id="itemsInPage" name="state">
                <?php foreach ($allowedPerPage as $opt): ?>
                    <option value="<?= $opt ?>" <?= $per_page === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="mt-4">
        <div class="custom-part-with-border">
            <div class="d-flex  justify-content-between gap-sm-2">
                <div class="d-flex">
                    <select class="js-example-basic-single halfSelectForm" name="state" id="xx">
                        <option><?= __("group_work") ?></option>
                        <option><?= __("group_work") ?></option>
                        <option><?= __("group_work") ?></option>
                    </select>

                    <button class="btn btn-primary mx-2" style="width: 59px;height: 50px;"><?= __("execution") ?></button>
                </div>
                <form method="get" class="d-flex align-items-center gap-2 mb-2">
                    <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                    <div class="input-group">
                        <input type="text" class="form-control border-end-0" name="search" id="search"
                               value="<?= htmlspecialchars($search) ?>" placeholder="<?= __("search") ?>">
                        <?php if(empty($search)):?>
                            <button class="btn btn-search border-start-0" type="submit" id="btn-search">
                                <i class='fas fa-search'></i>
                            </button>
                        <?php else:?>
                        <?php if (!empty($search)): ?><button class="btn btn-search border-start-0" type="button" id="btn-delete">
                            <a href="?filter=<?= htmlspecialchars($filter) ?>&page=1&per_page=<?= $per_page ?>" class="center text-decoration-none"><i class='fas fa-xmark text-primary'></i></a>
                        </button>
                        <?php endif;?>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="d-flex flex-wrap justify-content-between mt-5">
                <div class="d-flex" id="filterLinks">
                    <a href="?filter=all<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none "><?= __("all") ?> (<?= $all ?>)</a>
                    <div class="vertical-separator"></div>
                    <a href="?filter=categories<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none">
                        <?= __("category") ?> (<?= $categorySize ?>)
                    </a>
                    <div class="vertical-separator"></div>
                    <a href="?filter=services<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none "><?= __("services") ?> (<?= $serviceSize ?>)</a>
                </div>
                <?php if(isset($_GET['search'])): ?>
                <div><?php printf( __("item"),$items) ?></div>
                <?php endif; ?>
            </div>

        </div>
        <div class="mt-5">
            <?php if(!empty($allServices)): ?>
            <div class="table-wrapper">
                    <div class="table-responsive ">
                    <table class="table custom-table">
                    <thead class="table-primary ">
                    <tr>
                        <th> <input class="form-check-input checkBox" type="checkbox" value="" id="allServices">
                            <label for="allServices"><?= __("row") ?></label>
                        </th>
                        <th class="text-center" >
                            <a href="<?= $sortTitleUrl ?>" class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                <?= __('category_title')?>
                                <?php if ($sortBy === 'title'): ?>
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
                        <th >
                            <a href="<?= $sortCategoryUrl ?>" class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                <?= __('category')?>
                                <?php if ($sortBy === 'category'): ?>
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
                            <a href="<?= $sortServiceCountUrl ?>" class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                <?= __('category')?>
                                <?php if ($sortBy === 'count'): ?>
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
                                    <div class="d-flex  justify-content-between">
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
                                        <i class="fa fa-ellipsis-vertical fs-4"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end p-0 action-menu" aria-labelledby="navbarDropdownMenuLink">

                                        <li>
                                            <a class="dropdown-item text-start editServiceBtn"
                                               type="button"
                                               data-bs-target="#editModal"
                                               data-bs-toggle="modal"
                                               data-id="<?= $service->id ?>">
                                                <?= __("edit") ?>
                                            </a>
                                            <meta name="csrf-token" content="{{ csrf_token() }}">

                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-start " data-bs-toggle="modal" data-bs-target="#showDialogDelete<?= $service->id ?>"><?=__("delete")?></a></li>
                                    </ul>
                                </div>
                            </td>
                            <form method="post" action="<?= BASE_URL ?>/admin/services/delete/<?= $service->id ?>" id="editServiceForm">
                                <?= csrf_field() ?>
                                <div class="modal fade borderless-modal" id="showDialogDelete<?= $service->id ?>" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body custom-modal-body mt-4 mb-4">
                                                <h4 class="fw-bold">آیا از حذف خدمت هستید؟</h4>
                                                <div class="d-flex gap-4 mt-5">
                                                    <button type="submit" class="btn btn-primary btn-modal">بله</button>
                                                    <button type="button" class="btn btn-outline-secondary btn-modal" data-bs-dismiss="modal">خیر</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="editForm" method="post" >
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" id="service_id">
                                            <div class="modal-body2">
                                                <span class="center"><?= __("edit_service") ?></span>
                                                <input type="hidden" name="id" id="service_id">
                                                <div class="mt-4">
                                                    <label for="fa_title_modal"><?= __("title") ?><span class="bullet-color"> *</span></label>
                                                    <input type="text" class="form-control" name="fa_title" id="fa_title_modal">
                                                </div>
                                                <div class="mt-4">
                                                    <label for="en_title_modal"><?= __("english_title") ?><span class="bullet-color"> *</span></label>
                                                    <input type="text" class="form-control" name="en_title" id="en_title_modal" >
                                                </div>
                                                <div class="mt-4">
                                                    <label class="form-check-label" for="checkBoxCategory"><?=__("add_as_service_category") ?></label>
                                                    <div class="form-check form-switch ">
                                                        <input class="switch-input form-check-input" type="checkbox" role="switch"
                                                               id="checkBoxCategory" name="checkBoxCategory">
                                                    </div>
                                                </div>


                                                <div class="mt-4">
                                                    <label><?= __("select_category") ?><span class="bullet-color"> *</span></label>
                                                    <select  class="js-example-basic-single2 form-select w-100"  name="service">
<!--                                                    <select class="a  form-control" name="category" id="category_modal" --><?php ///*= ($service->parent_id == 0)  ? 'disabled' : '' */?><!-->-->
                                                        <?php foreach ($services as $ser): ?>
                                                            <option value="<?= $ser->id ?>"
                                                                <?= ($ser->id == ($service->parent_id ?? 0) ? 'selected' : '') ?>>
                                                                <?= ($lang == "fa") ? htmlspecialchars($ser->fa_title ?? '') : htmlspecialchars($ser->en_title ?? '') ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="row g-2 px-5 mt-4">
                                                    <div class="col-6">
                                                        <button type="submit" class="btn btn-primary w-100 py-2"><?= __("edit_service") ?></button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-outline-secondary w-100 py-2" data-bs-dismiss="modal"><?= __("cancel") ?></button>
                                                    </div>
                                                </div>
                                            </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </tr>
                        <?php $count++ ?>
                    <?php endforeach;?>
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
<script src="<?= asset('/js/edit-service.js') ?>"></script>
</body>

