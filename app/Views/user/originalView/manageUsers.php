<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div>
    <div class="mt-5"><a href="<?= BASE_URL ?>/admin/user/add">
            <button class="btn btn-outline-primary py-2"><?= __("add_new_user") ?></button>
        </a></div>
    <div class="mt-4 testi">
        <label for="paginationOption"><?= __("item_per_page") ?></label>
        <select class="js-example-basic-single sectionPagination" name="state" id="itemsInPage">
            <?php foreach ($allowedPerPage as $opt): ?>
                <option value="<?= $opt ?>" <?= $per_page === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mt-4">
        <div class="col-12 ">
            <div class="custom-part-with-border ">
                <div class="d-flex  flex-wrap justify-content-between gap-sm-2">
                    <div class="d-flex">
                        <select class="js-example-basic-single halfSelectForm" name="state" >
                            <option><?= __("group_work") ?></option>
                            <option><?= __("group_work") ?></option>
                            <option><?= __("group_work") ?></option>
                        </select>

                        <button class="btn btn-primary mx-1" style="width: 59px;height: 50px;"><?= __("execution") ?></button>
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
                        <a href="?filter=all<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark"><?= __("all") ?> (<?= $allUsers ?>)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=customers<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark">
                            <?= __("customer") ?> (<?= $customersSize ?>)
                        </a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=employees<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark"><?= __("employee") ?> (<?= $employeesSize ?>)</a>
                        <div class="vertical-separator"></div>
                        <a href="?filter=operators<?= (!empty($search)) ? "&search=" . urlencode($search) : "" ?>" class="text-decoration-none text-dark"><?= __("operator") ?> (<?= $operatorsSize ?>)</a>
                    </div>
                    <?php if(isset($_GET['search'])): ?>
                        <div><?php printf( __("item"), $searchSize) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <?php if (!empty($users)): ?>

            <div class="table-wrapper mt-3">
                <div class="table-responsive ">
                    <table class="table custom-table">
                        <thead class="table-primary ">
                        <tr>
                            <th> <input class="form-check-input checkBox" type="checkbox" value="" id="allUsers">
                                <label for="allUsers"><?= __("row") ?></label>
                            </th>
                            <th class="text-center" >
                                <a href="<?= $sortFirstNameUrl ?>" class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                    <?= __('name')?>
                                    <?php if ($sortBy === 'first_name'): ?>
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
                            <th class="text-center" >
                                <a href="<?= $sortLastNameUrl ?>" class="text-decoration-none text-white d-flex justify-content-center align-items-center">
                                    <?= __('last_name')?>
                                    <?php if ($sortBy === 'last_name'): ?>
                                        <?php if ($sortOrder === 'asc'): ?>
                                            <i class="fa-solid fa-caret-up mx-1"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-caret-down mx-1"></i>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <i class="fas fa-caret-down mx-1"></i>
                                    <?php endif; ?>
                                </a>
                            </th>                            <th><?= __("phone_number") ?></th>
                            <th><?= __("role") ?></th>
                            <th><?= __("score") ?> <i class="fas fa-sort-amount-down-alt mx-1"></th>
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
                                <input class="form-check-input checkBox" type="checkbox" value="" id="tableService<?= $count?>">
                                <label class="form-check-label" for="tableService<?= $count?>">
                                    <?=  htmlspecialchars($count)?>
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
                                        <?=__("has_not")?>
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
                                                   data-id="<?= $user->id ?>">
                                                    <?= __("edit") ?>
                                                </a>
                                                <meta name="csrf-token" content="{{ csrf_token() }}">

                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-start " data-bs-toggle="modal" data-bs-target="#showDialogDelete<?= $user->id ?>"><?=__("delete")?></a></li>
                                        </ul>
                                    </div>
                            </td>
                        </tr>
                                <form method="post" action="<?= BASE_URL ?>/admin/user/delete/<?= $user->id ?>" id="editServiceForm">
                                    <?= csrf_field() ?>
                                    <div class="modal fade borderless-modal" id="showDialogDelete<?= $user->id ?>" tabindex="-1" aria-labelledby="borderlessModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body custom-modal-body mt-4 mb-4">
                                                    <h4 class="fw-bold">آیا از حذف کاربر هستید؟</h4>
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
                                                <input type="hidden" name="id" id="user_id">
                                                <div class="modal-body2">
                                                    <span class="center"><?= __("edit") ?></span>
                                                    <input type="hidden" name="id" id="user_id">
                                                    <div class="mt-4">
                                                        <label for="first_name"><?= __("first_name") ?><span class="bullet-color"> *</span></label>
                                                        <input type="text" class="form-control" name="first_name" id="first_name">
                                                    </div>
                                                    <div class="mt-4">
                                                        <label for="last_name"><?= __("last_name") ?><span class="bullet-color"> *</span></label>
                                                        <input type="text" class="form-control" name="last_name" id="last_name">
                                                    </div>
                                                    <div class="mt-4">
                                                        <label for="phone_number"><?= __("phone_number") ?><span class="bullet-color"> *</span></label>
                                                        <input type="text" class="form-control" name="phone_number" id="phone_number" >
                                                    </div>
                                                    <div class="mt-4">
                                                        <label><?= __("user_type") ?><span class="bullet-color"> *</span></label>
                                                        <select class="form-control" name="roles" id="user_role">
                                                            <?php foreach ($userType as $role): ?>
                                                                <option value="<?= $role->id ?>"<?= ($role->id == ($user->user_type ?? 0) ? 'selected' : '') ?>>
                                                                    <?= ($lang == "fa") ? htmlspecialchars($role->title ?? '') : htmlspecialchars($role->en_title) ?>

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
                                <?php $count++ ?>
                        <?php endforeach;?>



                        </tbody>
                    </table>
                </div>
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
    <?php endif;?>
</div>
</div>
</div>
</div>
</body>
<script>

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
    document.getElementById('editModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const userId = button.dataset.id;

        fetch(`${BASE_URL}/admin/user/getUserData/${userId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('user_id').value = data.id || '';
                document.getElementById('first_name').value = data.first_name || '';
                document.getElementById('last_name').value = data.last_name || '';
                document.getElementById('phone_number').value = data.phone_number || '';
                document.getElementById('user_role').value = data.role_id || '';

            })
            .catch(error => console.error('Error fetching service:', error));
    });

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const userId = document.getElementById('user_id').value;
        fetch(`${BASE_URL}/admin/user/update/${userId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(res => res.json())
            .then(data => {
                console.log('User data:', data);
                if (data.success) {
                    alert(data.message);
                    $('#editModal').modal('hide');
                    location.reload();
                } else {
                    alert(data.message || 'خطا در بروزرسانی سرویس');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('خطا در بروزرسانی سرویس');
            });
    });

    $('#itemsInPage').on('change', function () {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    });
</script>
<script src="<?= asset('/js/register-user.js') ?>"></script>
