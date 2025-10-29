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
                    <div class="d-flex">
                        <select class="js-example-basic-single halfSelectForm" name="state" id="xx">
                            <option><?= __("group_work") ?></option>
                            <option><?= __("group_work") ?></option>
                            <option><?= __("group_work") ?></option>
                        </select>

                        <button class="btn btn-primary mx-1" style="width: 59px;height: 50px;"><?= __("execution") ?></button>
                    </div>
                    <div class="input-with-icon-left">
                        <i class="fa fa-search text-primary"></i>
                        <input type="text" class="form-control " id="search" name="search" placeholder="<?= __("search_users") ?>">
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between mt-5">
                    <div class="d-flex" id="filterLinks">
                        <a href="?filter=all<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark"><?= __("all") ?> (1)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=categories<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark">
                            <?= __("customer") ?> (4)
                        </a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=services<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark"><?= __("employee") ?> (2)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=services<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark"><?= __("operator") ?> (3)</a>
                    </div>
                    <?php if(isset($_GET['search'])): ?>
                        <div><?php printf( __("item"), 4) ?></div>
                    <?php endif; ?>
                </div>
<!--                <div class="d-flex flex-wrap justify-content-between  mt-4">-->
<!--                    <div class="d-flex">-->
<!--                        <span class="text-primary sortUserRole">--><?php //= __("all") ?><!-- (8)</span>-->
<!--                        <div class="vertical-separator"></div>-->
<!--                        <span class= " sortUserRole">--><?php //= __("customer") ?><!-- (2)</span>-->
<!--                        <div class="vertical-separator"></div>-->
<!--                        <span class="sortUserRole">--><?php //= __("employee") ?><!-- (4)</span>-->
<!--                        <div class="vertical-separator"></div>-->
<!--                        <span class="sortUserRole"> --><?php //= __("operator") ?><!-- (1)</span></div>-->
<!--                    <span class="sortUserRole">8 --><?php //= __("item") ?><!--</span>-->
<!--                </div>-->
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
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                        <tr>
                         <td>
                             <input class="form-check-input" type="checkbox" value="" id="tableUser<?=$user->id ?>">
                             <label class="form-check-label" for="tableUser<?=$user->id ?>">
                                 <?= $user->id ?>
                             </label>
                         </td>
                            <td><?= $user->first_name ?></td>
                            <td><?= $user->last_name ?></td>
                            <td><?= $user->phone_number ?></td>
                            <td><?= $user->user_type ?></td>
                            <td>
                                <?php if($user->user_type == 'کارمند'): ?>
                                    <?php if(($user->result != "")): ?>
                                        <?= $user->result ?>
                                    <?php else: ?>
                                        ندارد
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>

                            </td>
                            <td>
                                <?php if($user->user_type == 'کارمند'): ?>
                                    <?php
                                    $services = $user->services_name ? explode(', ', $user->services_name) : [];
                                    foreach($services as $service): ?>
                                        <div class="badge bg-badge-gray"><?= htmlspecialchars($service) ?></div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>

                            <td></td>
                        </tr>
                        <?php endforeach;?>
                        <?php endif?>
<!--                        <tr>-->
<!--                            <td>-->
<!--                                <input class="form-check-input" type="checkbox" value="" id="tableUser1">-->
<!--                                <label class="form-check-label" for="tableUser1">-->
<!--                                    1-->
<!--                                </label>-->
<!--                            </td>-->
<!--                            <td>هانیه</td>-->
<!--                            <td>محمدپور</td>-->
<!--                            <td>09211111111</td>-->
<!--                            <td>مدیر</td>-->
<!--                            <td>5</td>-->
<!--                            <td> <div class="badge  bg-badge-gray">رنگ مو</div></td>-->
<!--                            <td style="justify-items: center">-->
<!--                                <div class="dropdown">-->
<!--                                    <button class="btn btn-active  py-3 rounded-4 d-flex align-items-center activities-icon"-->
<!--                                            id="navbarDropdownMenuLink"-->
<!--                                            type="button"-->
<!--                                            data-bs-toggle="dropdown"-->
<!--                                            aria-expanded="false">-->
<!--                                        <span class="px-2"><i class="fa fa-ellipsis-vertical"></i></span>-->
<!--                                    </button>-->
<!--                                    <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="navbarDropdownMenuLink">-->
<!--                                        <li><a class="dropdown-item" href="#">ویرایش</a></li>-->
<!--                                        <li><hr class="dropdown-divider"></li>-->
<!--                                        <li><a class="dropdown-item">رزرو</a></li>-->
<!--                                        <li><hr class="dropdown-divider"></li>-->
<!--                                        <li><a class="dropdown-item">حذف</a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->


                        </tbody>

                    </table>
                </div>
            </div>
            </div>

        </div>
    <div class="mt-4">
        <div class="d-flex justify-content-end">
            <?php echo $renderPagination; ?>

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
