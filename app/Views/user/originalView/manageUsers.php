<div class="d-lg-none">
    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"
            aria-controls="sidebar">
        <i class="fa fa-bars"></i>
    </button>
</div>
<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>

<div>
    <div class="mt-5"><a href="<?= BASE_URL ?>/user/add">
            <button class="btn btn-outline-primary py-2">افزودن کاربر جدید</button>
        </a></div>
    <div class="mt-4">
        <label for="paginationOption">تعداد در هر صفحه:</label>
        <select id="paginationOption" class="paginationSelect py-1 px-1">
            <option>10</option>
            <option>20</option>
            <option>30</option>
            <option>40</option>
            <option>50</option>
        </select>
    </div>
    <div class="mt-4">
        <div class="col-12 ">
            <div class="custom-part-with-border ">
                <div class="d-flex justify-content-between">
                    <div>
                        <select class="js-example-basic-single test h-40-custom" name="state" id="xx">
                            <option>کارهای دسته جمعی</option>
                            <option>کارهای دسته جمعی</option>
                            <option>کارهای دسته جمعی</option>
                        </select>

                        <button class="btn btn-primary">اجرا</button>
                    </div>
                    <div class="input-with-icon-left">
                        <i class="fa fa-search text-primary"></i>
                        <input type="text" class="form-control " id="search" name="search" placeholder="جستجوی کاربران">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <div><span class="text-primary with-border sortUserRole">همه (8)</span>
                        <span class= "with-border sortUserRole">مشتری (2)</span>
                        <span class="with-border sortUserRole">کارمند (4)</span>
                        <span class="sortUserRole"> اپراتور (1)</span></div>
                    <span class="sortUserRole">8 مورد</span>
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
                                    ردیف
                                </label></th>
                            <th>نام</th>
                            <th>نام خانوادگی</th>
                            <th>شماره موبایل</th>
                            <th>نقش کاربر</th>
                            <th>امتیاز <i class="fas fa-sort-amount-down-alt mx-1"></th>
                            <th>خدمات</th>
                            <th>عملیات</th>
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
                        </tr>

                        </tbody>

                    </table>
                </div>
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
