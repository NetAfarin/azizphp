
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
           <div class="testi d-flex align-items-baseline">
               <label for="itemsInPage" class="px-2"><?= __("item_per_page") ?></label>
               <select class="js-example-basic-single sectionPagination" id="itemsInPage" name="state">
                   <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                   <option value="20" <?= $per_page == 20 ? 'selected' : '' ?>>20</option>
                   <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
               </select>
    </div>
    <div class="mt-4">
        <div class="custom-part-with-border">
            <div class="d-flex  justify-content-between gap-sm-2">
                <div class="d-flex">
                    <select class="js-example-basic-single " name="state" id="xx">
                        <option><?= __("group_work") ?></option>
                        <option><?= __("group_work") ?></option>
                        <option><?= __("group_work") ?></option>
                    </select>

                    <button class="btn btn-primary mx-2" style="width: 59px;height: 50px;"><?= __("execution") ?></button>
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
                <?php if(isset($_GET['search'])): ?>
                <div><?= $items ?> <?=  __("item") ?></div>
                <?php endif; ?>
            </div>

        </div>
        <?php if(!empty($allServices)): ?>
        <div class="mt-5">
                <div class="table-wrapper">
                    <div class="table-responsive ">
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
                                        <i class="fa fa-ellipsis-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end p-0 action-menu" aria-labelledby="navbarDropdownMenuLink">

                                        <li>
                                            <a class="dropdown-item text-start editServiceBtn"
                                               data-id="<?= $service->id ?>"
                                               data-fa="<?= htmlspecialchars($service->title) ?>"
                                               data-en="<?= htmlspecialchars($service->en_title) ?>"
                                               data-category="<?= $service->parent_id ?>"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editModal">
                                                <?= __("edit") ?>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-start " data-bs-toggle="modal" data-bs-target="#showDialogDelete<?= $service->id ?>">حذف</a></li>
                                    </ul>
                                </div>
                            </td>
                            <form method="post" action="<?= BASE_URL ?>/admin/services/delete/<?= $service->id ?>" id="editServiceForm">
                                <?= csrf_field() ?>
                                <div class="modal fade borderless-modal" id="showDialogDelete<?= $service->id ?>" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body custom-modal-body mt-4 mb-4">
                                                <h4 class="fw-bold">آیا از حذف کاربر خدمت هستید؟</h4>
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
                                        <form id="editServiceForm">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="service_id">
                                                <div class="mb-3">
                                                    <label for="fa_title_modal"><?= __("title") ?></label>
                                                    <input type="text" class="form-control" name="fa_title" id="fa_title_modal">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="en_title_modal"><?= __("en_title") ?></label>
                                                    <input type="text" class="form-control" name="en_title" id="en_title_modal" >
                                                </div>
                                                <div class="form-check form-switch mt-4">
                                                    <input class="switch-input form-check-input" type="checkbox" role="switch"
                                                           id="serviceCategory_modal" name="serviceCategory"
                                                        <?= ($service->parent_id == 0) ? 'checked' : '' ?>>
                                                </div>

                                                <div class="mb-3">
                                                    <label><?= __("select_category") ?></label>
                                                    <select class="form-control" name="category" id="category_modal">
                                                        <?php foreach ($services as $ser): ?>
                                                            <option value="<?= $ser->id ?>"
                                                                <?= ($ser->id == ($service->parent_id ?? 0) ? 'selected' : '') ?>>
                                                                <?= ($lang == "fa") ? htmlspecialchars($ser->fa_title ?? '') : htmlspecialchars($ser->en_title ?? '') ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>


                                            </div>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="submit" class="btn btn-primary"><?= __("save") ?></button>
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= __("cancel") ?></button>
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
        <div class="mt-4">
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
    const lang = document.documentElement.lang;
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
    $(document).ready(function() {
        $('#myForm').on('submit', function(e) {
            e.preventDefault(); // جلوگیری از ارسال فرم به صورت معمولی

            $.ajax({
                url: '/submit.php', // مسیر سرور
                method: 'POST',
                data: $(this).serialize(), // جمع آوری داده‌های فرم
                success: function(response) {
                    $('#result').html(response); // نمایش پاسخ سرور
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
    $('.editServiceBtn').on('click', function() {
        const id = $(this).data('id');
        const fa = $(this).data('fa');
        const en = $(this).data('en');
        const category = $(this).data('category');

        $('#service_id').val(id);
        $('#fa_title_modal').val(fa);
        $('#en_title_modal').val(en);

        // فقط چک‌باکس را روشن می‌کنیم اگر parent_id == 0
        $('#serviceCategory_modal').prop('checked', category == 0);

        // سلکت را بدون تغییر نگه می‌داریم
        $('#category_modal').val(category);
    });

    // دیگر نیاز به غیرفعال کردن سلکت نیست
    $('#serviceCategory_modal').on('change', function() {
        // فقط رفتار بصری یا دیگر منطق‌ها، سلکت را دست نزنیم
    });


</script>


</div>
</div>
</body>
<script src="<?= asset('/js/register-user.js') ?>"></script>
