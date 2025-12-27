<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div class="col mt-5">
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-md-4">
            <div class="card  shadow-md h-100 " >
                <div class="card-header text-center fw-bold bg-transparent fs-4"><?= __("visits_today_number") ?></div>
                <div class="card-body">
                    <p class="card-text flex-wrap d-flex align-items-center justify-content-center gap-3"><span class="text-primary" style="font-size: 45px"><?= $todayVisitCount ?> </span><?= __("active_reserve") ?></p>
                </div>
                <div class="custom-card-footer"><span></span></div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-md-4 mt-3 mt-lg-0  mt-md-0">
            <div class="card  shadow-md" >
                <div class="card-header text-center fw-bold bg-transparent fs-4"><?= __("services_done_today")?></div>
                <div class="card-body" style="--divider-offset: 1.25rem; padding: 20px;">
                    <div class="d-flex align-items-center flex-wrap  gap-1 mb-2">
                        <i class="fa-regular fa-circle-check text-primary"></i>
                        <span><?= __("services_done_today").":"?> </span>
                        <span class="text-primary"><?=$doneServicesCount ?></span>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1 mt-2 flex-wrap">
                        <i class="fa-regular fa-circle-check text-primary"></i>
                        <span><?= __("services_confirm").":"?> </span>
                        <span class="text-primary"><?=$pendingServicesCount ?></span>
                    </div>
                </div>
                <div class="custom-card-footer"><span></span></div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-md-4 mt-3 mt-lg-0  mt-md-0">
            <div class="card  shadow-md h-100 " >
                <div class="card-header text-center fw-bold bg-transparent fs-4"><?= __("all_customers") ?></div>
                <div class="card-body">
                    <p class="card-text flex-wrap d-flex align-items-center justify-content-center gap-3"><span class="text-primary" style="font-size: 45px"><?= $customersCount ?> </span><?= __("person") ?></p>
                </div>
                <div class="custom-card-footer"><span></span></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-sm-12 col-md-8">
            <div class="col-lg-12 col-sm-12 mt-5">
                <div class="d-flex py-4 rounded bg-white flex-column justify-content-evenly shadow-md">
                    <div class="p-2 fw-bold text-center "><?= __("click_to_create_reserve") ?></div>
                    <div class="p-2 text-secondary text-center">
                        <a href="/fw/admin/bookings/create">
                            <button class="btn btn-primary"><?= __("create_new_reserve") ?></button>
                        </a>
                    </div>
                </div>
            </div>
       <div class="mt-5">
           <div class="row">
               <div class="col-lg-4 col-xxl-7 col-xl-5 col-md-5  col-sm-4 d-flex align-items-center">
                   <h4 class="fw-bold"><?= __("visits_today_number") ?></h4>
               </div>

               <div class="col-lg-8 col-xxl-5  col-xl-7 col-md-7 col-sm-8">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                          <select class="js-example-basic-single halfSelectForm" name="filter" id="changeStatus">
                              <option value="all"><?= __("all_status") ?></option>
                              <?php foreach ($visitStatus as $status):?>
                              <option value="<?= $status->id ?>" <?= ( $status->id == $getStatus) ? 'selected' : ''  ?>><?= (APP_LANG == 'fa') ?$status->fa_title : $status->en_title ?></option>
                              <?php endforeach ?>
                          </select>
                      <form method="get" class="d-flex align-items-center w-100">
                              <input type="hidden" name="filter" value="<?= htmlspecialchars($getStatus) ?>">
                          <div class="input-group">
                              <input type="text" class="form-control border-end-0" name="search" id="search"
                                     value="<?= htmlspecialchars($search) ?>" placeholder="<?= __("search") ?>">
                              <?php if(empty($search)):?>
                                  <button class="btn btn-search border-start-0" type="submit" id="btn-search">
                                      <i class='fas fa-search'></i>
                                  </button>
                              <?php else:?>
                                  <?php if (!empty($search)): ?><button class="btn btn-search border-start-0" type="button" id="btn-delete">
                                      <a href="?filter=<?= htmlspecialchars($getStatus) ?>&page=1&per_page=<?= $per_page ?>" class="center text-decoration-none">
                                          <i class='fas fa-xmark text-primary'></i></a>
                                      </button>
                                  <?php endif;?>
                              <?php endif; ?>
                          </div>
                      </form>
                  </div>
               </div>
           </div>
           <div class="mt-5">
            <?php if(!empty($visits)):?>
           <div class="table-wrapper">
               <table class="table custom-table" id="allVisits">
                   <thead class="table-primary ">
                   <tr>
                       <th><?= __("row")?></th>
                       <th><?= __("first_name")?></th>
                       <th><?= __("last_name")?></th>
                       <th><?= __("services")?></th>
                       <th><?= __("status")?></th>
                       <th><?= __("actions")?></th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php $count = 1 ?>
                   <?php foreach ($visits as $item) :?>
                   <tr>
                       <td><?= htmlspecialchars($count) ?></td>
                       <td> <?= htmlspecialchars( $item->customerName) ?></td>
                       <td><?= htmlspecialchars($item->customerLastName) ?></td>
                       <td><?= htmlspecialchars($item->service) ?></td>
                       <td><?= htmlspecialchars($item->visitStatus) ?></td>
                       <td>
                           <div class="dropdown d-flex justify-content-center align-items-center">
                               <button class="btn btn-active activities-icon"
                                       type="button"
                                       data-bs-toggle="dropdown"
                                       data-bs-target="#dropdown-menu-<?= $item->id ?>"
                                       aria-expanded="false"
                                       data-item-id="<?= $item->id ?>">
                                   <i class="fa fa-ellipsis-vertical fs-4"></i>
                               </button>
                               <ul class="dropdown-menu dropdown-menu-start  p-0 action-menu " style="position: absolute;">
                                   <li>
                                       <a class="dropdown-item for-table d-flex align-items-center"
                                          href="#"
                                          data-bs-toggle="modal"
                                          data-bs-target="#changeStatusModal"
                                          data-employee_id="<?= $item->employeeId ?>"
                                          data-register_datetime="<?= $item->registerDatetime ?>"
                                          data-status="<?= $item->visitStatusId ?>"
                                          data-id="<?= $item->id ?>">
                                           <i class="fa fa-pen mx-2"></i>
                                           <?= __("change_status_rserve") ?>
                                           <meta name="csrf-token" content="{{ csrf_token() }}">
                                       </a>
                                   </li>
                                   <li><hr class="dropdown-divider"></li>
                                   <li>
                                       <a class="dropdown-item for-table d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#detailsModal_<?= $item->id ?>">
                                           <i class="fa fa-page mx-2"></i>
                                           <?= __("reserve_details") ?>
                                       </a>
                                   </li>
                               </ul>
                           </div>
                           <div class="modal fade" id="detailsModal_<?= $item->id ?>" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                               <div class="modal-dialog modal-dialog-centered">
                                   <div class="modal-content">
                                       <input type="hidden" name="id" id="service_id">
                                       <div class="modal-body2">
                                           <h5 class="center fw-bold"><?= __("reserve_details") ?></h5>
                                           <div class="reserveDerailsModal rounded text-start">
                                               <div class="d-flex flex-column gap-3 p-3">
                                                   <span><?= __("customer") ?>: <?= htmlspecialchars( $item->customerName) ." ". htmlspecialchars( $item->customerLastName) ?></span>
                                                   <span><?= __("service") ?>: <?= htmlspecialchars($item->service) ?></span>
                                                   <span><?= __("employee") ?>: <?= $item->employeeFirstName ?>  <?= $item->employeeLastName ?></span>
                                                   <span><?= __("status") ?>:  <?= htmlspecialchars($item->visitStatus) ?></span>
                                                   <span><?= __("register_time") ?>: <?= toJalali($item->visitDate)['time'] ?></span>
                                                   <span><?= __("register_date") ?>: <span><?= toJalali($item->visitDate)['date'] ?></span></span>
                                                   <!--                                       <span>--><?php //= __("code") ?><!--: 123456</span>-->
                                                   <span><?= __("submitDate") ?>:  <?= toJalali($item->registerDatetime)['date'] ?></span>
                                               </div>
                                           </div>
                                           <button class="btn btn-outline-primary px-5 mt-4"
                                                   style="margin-top: 35px;"
                                                   data-bs-dismiss="modal"><?= __("close2") ?></button>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           <div class="modal fade" id="changeStatusModal" tabindex="-1">
                               <div class="modal-dialog modal-dialog-centered">
                                   <div class="modal-content">
                                       <form id="changeStatusForm" method="post" >
                                           <?= csrf_field() ?>
                                           <input type="hidden" name="id" id="status_id">
                                           <input type="hidden" name="employee_id" id="employee_id">
                                           <input type="hidden" name="register_datetime" id="register_datetime">
                                           <div class="modal-body2">
                                               <h5 class="center fw-bold"><?= __("change_status_rserve") ?></h5>
                                               <div class="reserveDerailsModal rounded text-start">
                                                   <div class="d-flex flex-column align-items-center gap-3 p-3">
                                                       <select class="js-example-basic-single " name="status_id" id="changeStatusSelect">
                                                           <?php foreach ($visitStatus as $status):?>
                                                               <option value="<?= $status->id ?>">
                                                                   <?= (APP_LANG == 'fa') ? $status->fa_title : $status->en_title ?>
                                                               </option>
                                                           <?php endforeach ?>
                                                       </select>
                                                   </div>
                                               </div>
                                               <button class="btn btn-outline-primary px-5 mt-4" style="margin-top: 35px;" type="submit">
                                                   <?= __("edit") ?>
                                               </button>
                                           </div>
                                       </form>
                                   </div>
                               </div>
                           </div>
                     </td>
                   </tr>
                       <?php $count ++; ?>
             <?php endforeach;?>
                   </tbody>
               </table>
           </div>
                <?php if ($page > 1):?>
                    <div class="mt-3">
                        <div class="d-flex mb-0 justify-content-end align-items-center">
                            <?php echo $renderPagination; ?>
                        </div>
                    </div>
                <?php endif;?>
            <?php else:?>
                <div class="alert alert-danger"><?= __("not_found") ?></div>
            <?php endif;?>
           </div>
       </div>
            <div class="mt-5">
                <div class="card  shadow-md" >
                    <div class="card-header  fw-bold bg-transparent" style="--divider-offset: 1.25rem; padding: 27px;"><h4 class="fw-bold"><?= __("yesterday_report") ?></h4></div>
                    <div class="card-body" style="--divider-offset: 1.25rem; padding: 0 27px 0 27px ;">
                            <div class="my-2 text-start">
                                <i class="fa-regular fa-circle-check text-primary"></i>
                                <span>
                             <?php echo ($doneServiceYesterday== 0) ? __("customers_has_not_done_services") : str_replace('%s', $doneServiceYesterday, __("count_of_customers_had_services")); ?>
                                </span>
                            </div>
                            <div class="divider_lg"></div>
                        <div class="my-2 text-start">
                                <i class="fa-regular fa-circle-check text-primary"></i>
                            <?php echo ($cancelledServiceYesterday == 0) ? __("not_have_cancelled_reserves") : str_replace('%s', $cancelledServiceYesterday, __("cancelled_reserves")); ?>
                            </div>
                            <div class="divider_lg"></div>
                        <div class="my-2 text-start">
                                <i class="fa-regular fa-circle-check text-primary"></i>
                                <span><?= __("customers_happiness") ?>: 92%</span>
                            </div>
                    </div>
                    <div class="text-center" style="padding: 0 27px 27px 0 ;">
                        <button class="btn btn-primary py-2 px-4"><?= __("export_excel") ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-md-4">
            <div class="mt-5">
                <div class="shadow-md bg-white rounded py-4 px-4 vh-100">
                    <h4 class="text-start mb-3"><?= __("lastest_news") ?></h4>
                    <ul class="list-unstyled bg-white " >
                        <li class="d-flex align-items-center mb-3">
                            <i class="fa fa-circle text-primary mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-break"><?= __("tool_disinfection_time") ?></span>
                        </li>

                        <li class="d-flex align-items-center mb-3">
                            <i class="fa fa-circle text-primary mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-break"><?= __("work_start_time") ?></span>
                        </li>

                        <li class="d-flex align-items-center mb-3">
                            <i class="fa fa-circle text-primary mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-break"><?= __("post_keratin_care") ?></span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="fa fa-circle text-primary mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-break"><?= __("tool_disinfection_time") ?></span>
                        </li>

                        <li class="d-flex align-items-center mb-3">
                            <i class="fa fa-circle text-primary mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-break"><?= __("work_start_time") ?></span>
                        </li>

                        <li class="d-flex align-items-center mb-3">
                            <i class="fa fa-circle text-primary mt-1 me-2 flex-shrink-0"></i>
                            <span class="text-break"><?= __("post_keratin_care") ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?= asset('/js/operator.js') ?>"></script>