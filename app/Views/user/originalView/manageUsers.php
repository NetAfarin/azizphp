<div class="d-lg-none">
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
            aria-controls="sidebar">
        <i class="fa fa-bars"></i>
    </button>
</div>
<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>

<div>
    <div class="mt-5"><a href="<?= BASE_URL ?>/user/add">
            <button class="btn btn-outline-primary py-2"><?= __("add_new_user") ?></button>
        </a></div>
    <div class="mt-4 testi">
        <label for="paginationOption"><?= __("item_per_page") ?></label>
        <select class="js-example-basic-single sectionPagination" name="state">
            <option value="">10</option>
            <option value="">20</option>
            <option value="">50</option>
        </select>
    </div>
    <div class="mt-4">
        <div class="col-12 ">
            <div class="custom-part-with-border ">
                <div class="d-flex  flex-wrap justify-content-between gap-sm-2">
                    <div>
                        <select class="js-example-basic-single test h-40-custom" name="state" id="xx">
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
                    <div>
<!--                        --><?php //foreach ($roles as $role): ?>
                        <span class="text-primary with-border sortUserRole"><?= __("all") ?> (8)</span>
                        <span class= "with-border sortUserRole"><?= __("customer") ?> (2)</span>
                        <span class="with-border sortUserRole"><?= __("employee") ?> (4)</span>
                        <span class="sortUserRole"> <?= __("operator") ?> (1)</span></div>
                    <span class="sortUserRole">8 <?= __("item") ?></span>
<!--                    --><?// endforeach; ?>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="table-wrapper mt-3">
                <div class="table-responsive ">
                    <table class="table custom-table">
                        <thead class="table-primary ">
                        <tr>
                            <th> <input class="form-check-input" type="checkbox" value="" id="allUsers">
                                <label class="form-check-label" for="allUsers">
                                    <?= __("row") ?>
                                </label></th>
                            <th><?= __("name") ?></th>
                            <th><?= __("last_name") ?></th>
                            <th><?= __("phone_number") ?></th>
                            <th><?= __("role") ?></th>
                            <th><?= __("score") ?> <i class="fas fa-sort-amount-down-alt mx-1"></th>
                            <th><?= __("services") ?></th>
                            <th><?= __("actions") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input class="form-check-input" type="checkbox" value="" id="tableUser1">
                                <label class="form-check-label" for="tableUser1">
                                    1
                                </label>
                            </td>
                            <td>هانیه</td>
                            <td>محمدپور</td>
                            <td>09211111111</td>
                            <td>مدیر</td>
                            <td>5</td>
                            <td> <div class="badge  bg-badge-gray">رنگ مو</div></td>
                            <td style="justify-items: center">
                                <div class="dropdown">
                                    <button class="btn btn-active  py-3 rounded-4 d-flex align-items-center activities-icon"
                                            id="navbarDropdownMenuLink"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <span class="px-2"><i class="fa fa-ellipsis-vertical"></i></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdownMenuLink">
                                        <li><a class="dropdown-item" href="#">ویرایش</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item">رزرو</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item">حذف</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="dropdown">
                                <input class="form-check-input" type="checkbox" value="" id="tableUser2">
                                <label class="form-check-label" for="tableUser2">
                                    2
                                </label>
                                </div>
                            </td>
                            <td>هانیه</td>
                            <td>محمدپور</td>
                            <td>09211111111</td>
                            <td>مدیر</td>
                            <td> 5</td>
                            <td>
                                <div class="badge  bg-badge-gray">رنگ مو</div>
                                <div class="badge  bg-badge-gray">کوتاهی</div>
                            </td>
                            <td style="justify-items: center">
                                <div class="dropdown">
                                    <button class="btn btn-active rounded-4 activities-icon"
                                            id="navbarDropdownMenuLink"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="fa fa-ellipsis-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdownMenuLink">
                                        <li><a class="dropdown-item" href="#">ویرایش</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item">رزرو</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item">حذف</a></li>
                                    </ul>
                                </div>

                            </td>

                        </tr>

                        </tbody>

                    </table>
                </div>
            </div>
            </div>

        </div>
        <div class="row mt-4">
            <div class="d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>
</div>

</div>
</div>
</body>
<script>

</script>
<script src="<?= asset('/js/register-user.js') ?>"></script>
