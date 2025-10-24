
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
                                <?=( $ser->fa_title) ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
          <div class="d-flex justify-content-end">
              <button class="btn btn-primary mt-5 px-5" type="submit"><?= __("add")?></button>
          </div>

        </div>

        <div class="row mt-5">
            <div class="col-6 testi">
                <label for="paginationOption"><?= __("item_per_page") ?></label>
                <select class="js-example-basic-single sectionPagination" name="state">
                    <option value="">10</option>
                    <option value="">20</option>
                    <option value="">50</option>
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

                        <button class="btn btn-primary mx-1"><?= __("execution") ?></button>
                    </div>
                    <div class="input-with-icon-left">
                        <i class="fa fa-search text-primary"></i>
                        <input type="text" class="form-control " id="search" name="search" placeholder="<?= __("search_users") ?>">
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between mt-4">
                    <div class="d-flex">
                        <div><?= __("all") ?> (4)</div>
                        <div class="vertical-separator"></div>
                        <div><?= __("category") ?></div>
                        <div class="vertical-separator"></div>
                        <div><?= __("services") ?></div>
                </div>
                    <div>8 مورد</div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="table-wrapper">
                <table class="table custom-table">

                    <thead class="table-primary ">
                    <tr>
                        <th> <input class="form-check-input checkBox" type="checkbox" value="" id="allServices">
                            <label for="allServices">ردیف</label>
                        </th>
                        <th>عنوان</th>
                        <th>دسته بندی <i class="fas fa-sort-amount-down-alt mx-1"></i></th>
                        <th>تعداد خدمات <i class="fas fa-sort-amount-down-alt mx-1"></i></th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count =1; ?>
                    <?php foreach ($allServices as $service): ?>
                        <tr>
                        <td>
                            <input class="form-check-input checkBox" type="checkbox" value="" id="tableService<?= $service->id?>">
                            <label class="form-check-label" for="tableService<?= $service->id?>">
                                <?= $count?>
                            </label>
                        </td>
                        <td>
<!--                            --><?php //if (($service->parent_id) == 0): ?>
<!--                            <div class="d-flex justify-content-between " >-->
<!--                                <span class="badge category-bg-light-green " style="opacity: 0">دسته بندی</span>-->
<!--                                <span>--><?php //= __($service->fa_title) ?><!--</span>-->
<!--                                <span class="badge category-bg-light-green">دسته بندی</span>-->
<!--                            </div>-->
<!--                            --><?php //else:?>
<!--                                <div>-->
<!--                                    <span>--><?php //= __($service->fa_title) ?><!--</span>-->
<!--                                </div>-->
<!--                            --><?php //endif;?>


                        </td>
                        <td>
<!--                            --><?php //if ($ser->parent_id != 0):?>
<!--                                <span>-->
<!--                                    --><?php //if ($service->parent == null):?>
<!--                                    --->
<!--                                    --><?php //else:?>
<!--                                    --><?php //=$ser->parent ?>
<!--                                    --><?php //endif;?>
<!--                                </span>-->
<!--                            --><?php //endif ?>
                        </td>
                        <td>2</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn  activities-icon"><span class="fa fa-edit"></span></button>
                                <button class="btn  activities-icon"><span class="fa fa-trash"></span></button>
                            </div>
                        </td>

                    </tr>
                    <?php $count++?>
                    <?php endforeach;?>

                    </tbody>

                </table>
            </div>
        </div>


    </div>

</form>
<script>
    var selectAllServices = document.getElementById("allServices");
    selectAllServices.addEventListener("change", function () {
        var table = this.closest("table");
        var checkboxes = table.querySelectorAll("tbody input[type='checkbox']");
        checkboxes.forEach(cb => cb.checked = selectAllServices.checked);
    });
    // تابع برای toggle کردن نمایش
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
</script>


</div>
</div>
</body>
<script src="<?= asset('/js/register-user.js') ?>"></script>
