<?php use App\Models\UserType;

include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div>
    <div class="mt-5"><a href="<?= BASE_URL ?>/admin/user/add">
            <button class="btn btn-outline-primary py-2"><?= __("add_new_user") ?></button>
        </a></div>
    <div class="d-flex  align-items-baseline gap-2 mt-5">
        <label for="itemsInPage"><?= __("item_per_page") ?></label>
        <div>
            <select class="perPageSelect sectionPagination" id="itemsInPage" name="state">
                <?php foreach ($allowedPerPage as $opt): ?>
                    <option value="<?= $opt ?>" <?= $per_page === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <form method="post" action="<?= BASE_URL ?>/admin/users/delete" id="deleteForm">
        <?= csrf_field() ?>
        <input type="hidden" name="user_id" id="singleDeleteInput">
        <input type="hidden" name="user_ids[]" id="groupDeleteInput">
        <div class="modal fade borderless-modal" id="deleteUserModal"
             tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body custom-modal-body mt-4 mb-4">
                        <h4 class="fw-bold">آیا از حذف کاربر هستید؟</h4>
                        <div class="d-flex gap-4 mt-5">
                            <button type="submit" class="btn btn-primary btn-modal ">بله</button>
                            <button type="button" class="btn btn-outline-secondary btn-modal"
                                    data-bs-dismiss="modal">خیر
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="mt-4">
        <div class="col-12 ">
            <div class="custom-part-with-border ">
                <div class="d-flex  flex-wrap justify-content-between gap-sm-2">
                    <div class="d-flex">
                        <select class="groupWorkSelect halfSelectForm"
                                onchange="">
                            <option value="delete"><?= __("delete") ?></option>
                            <option><?= __("group_work") ?></option>
                            <option><?= __("group_work") ?></option>
                        </select>
                        <button type="submit"
                                form="deleteForm"
                                class="btn btn-primary mx-1 groupDelete"
                                style="width: 59px;height: 50px;">
                            <?= __("execution") ?>
                        </button>
                    </div>
                    <form method="get" class="d-flex align-items-center gap-2 mb-2">
                        <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
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
                                    <a href="?filter=<?= htmlspecialchars($filter) ?>&page=1&per_page=<?= $per_page ?>"
                                       class="center text-decoration-none"><i class='fas fa-xmark text-primary'></i></a>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="d-flex flex-wrap justify-content-between mt-5">
                    <div class="d-flex" id="filterLinks">
                        <a href="?filter=all<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>"
                           class="text-decoration-none text-dark"><?= __("all") ?> (<?= $allUsers ?>)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=customers<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>"
                           class="text-decoration-none text-dark">
                            <?= __("customer") ?> (<?= $customersSize ?>)
                        </a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=employees<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>"
                           class="text-decoration-none text-dark"><?= __("employee") ?> (<?= $employeesSize ?>)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=operators<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>"
                           class="text-decoration-none text-dark"><?= __("operator") ?> (<?= $operatorsSize ?>)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=manager<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>"
                           class="text-decoration-none text-dark"><?= __("manager") ?> (<?= $adminsSize ?>)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=super-admin<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>"
                           class="text-decoration-none text-dark"><?= __("super_admin") ?> (<?= $superAdminsSize ?>)</a>
                    </div>
                    <?php if (isset($_GET['search'])): ?>
                        <div><?php printf(__("item"), $searchSize) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <?php if (!empty($users)): ?>
            <div class="table-wrapper mt-3">
                    <table class="table custom-table">
                        <thead class="table-primary ">
                        <tr>
                            <th><input class="form-check-input checkBox" type="checkbox" value="" id="allUsers">
                                <label for="allUsers"><?= __("row") ?></label>
                            </th>
                            <th class="text-center">
                                <a href="<?= $sortFirstNameUrl ?>"
                                   class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                    <?= __('name') ?>
                                    <?php if ($sortBy === 'firstname'): ?>
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
                            <th class="text-center">
                                <a href="<?= $sortLastNameUrl ?>"
                                   class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                    <?= __('last_name') ?>
                                    <?php if ($sortBy === 'lastname'): ?>
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
                            <th><?= __("phone_number") ?></th>
                            <th><?= __("role") ?></th>
                            <th class="text-center">
                                <a href="<?= $sortScoreUrl ?>"
                                   class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                    <?= __('score') ?>
                                    <?php if ($sortBy === 'score'): ?>
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
                            <th><?= __("services") ?></th>
                            <th><?= __("actions") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $startNumber = (($pagination['current_page'] - 1) * $per_page) + 1;
                        $count = $startNumber;
                        ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <input class="form-check-input checkBox"
                                           type="checkbox"
                                           name="users[]"
                                           value="<?= $user->id ?>"
                                           form="groupActionForm"
                                           id="tableService<?= $count ?>">
                                    <label class="form-check-label" for="tableService<?= $count ?>">
                                        <?= htmlspecialchars($count) ?>
                                    </label>
                                </td>
                                <td><?= $user->first_name ?></td>
                                <td><?= $user->last_name ?></td>
                                <td><?= $user->phone_number ?></td>
                                <td><?= $user->user_type ?></td>
                                <td>
                                    <?php
                                    if($user->type_id != UserType::EMPLOYEE){
                                        echo "-";
                                    }else{
                                        if($user->result == ""){
                                            echo __("has_not");
                                        }else{
                                            echo round($user->result, 1);;
                                        }
                                    }

                                    ?>
                                </td>
                                <td>
                                    <?php if ($user->type_id == UserType::EMPLOYEE): ?>
                                        <?php $services = $user->services_name ? explode(', ', $user->services_name) : [];
                                        ?>
                                        <?php if (!empty($services)): ?>
                                            <?php foreach ($services as $service): ?>
                                                <div class="badge bg-badge-gray"><?= htmlspecialchars($service) ?></div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="dropdown d-flex justify-content-center align-items-center position-relative">
                                        <div class="dropdown">
                                            <button class="btn btn-active activities-icon"
                                                    id="navbarDropdownMenuLink"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                <i class="fa fa-ellipsis-vertical fs-4"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-start  p-0 action-menu "
                                                style="position: absolute;">
                                                <li>
                                                    <a class="dropdown-item for-table d-flex align-items-center"
                                                       href="/fw/admin/user/edit/<?= $user->id ?>">
                                                        <?= __("edit") ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-start single-delete"
                                                       data-user-id="<?= $user->id ?>">
                                                        <?= __("delete") ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                </td>
                            </tr>
                            <form method="post" action="<?= BASE_URL ?>/admin/user/delete/<?= $user->id ?>"
                                  id="editServiceForm">
                                <?= csrf_field() ?>
                                <div class="modal fade borderless-modal" id="showDialogDelete<?= $user->id ?>"
                                     tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body custom-modal-body mt-4 mb-4">
                                                <h4 class="fw-bold">آیا از حذف کاربر هستید؟</h4>
                                                <div class="d-flex gap-4 mt-5">
                                                    <button type="submit" class="btn btn-primary btn-modal">بله</button>
                                                    <button type="button" class="btn btn-outline-secondary btn-modal"
                                                            data-bs-dismiss="modal">خیر
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php $count++ ?>
                        <?php endforeach; ?>


                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    <?php if ($page > 1): ?>
        <div class="mt-3">
            <div class="d-flex mb-0 justify-content-end align-items-center">
                <?php echo $renderPagination; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-danger"><?= __("not_found") ?></div>
    <?php endif; ?>
</div>
</div>
</div>
</div>
<script src="<?= asset('/js/user/manage-users.js') ?>"></script>
