<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>
<div class="col mt-5">
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-md-4">
            <div class="card  shadow-md h-100 " >
                <div class="card-header text-center fw-bold bg-transparent fs-4">نوبت های امروز</div>
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
                        <span>کوتاهی مو: </span>
                        <span class="text-primary">2 روز پیش</span>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1 mt-2 flex-wrap">
                        <i class="fa-regular fa-circle-check text-primary"></i>
                        <span>کوتاهی مو: </span>
                        <span class="text-primary">2 روز پیش</span>
                    </div>
                </div>
                <div class="custom-card-footer"><span></span></div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-md-4 mt-3 mt-lg-0 mt-md-0">
            <div class="card  shadow-md" >
                <div class="card-header text-center fw-bold bg-transparent fs-4">مشتری های حاضر</div>
                <div class="card-body" style="--divider-offset: 1.25rem; padding: 20px;">
                    <div class="d-flex align-items-center flex-wrap  gap-1 mb-2">
                        <i class="fa-regular fa-circle-check text-primary"></i>
                        <span>کوتاهی مو: </span>
                        <span class="text-primary">2 روز پیش</span>
                    </div>
                    <div class="divider"></div>
                    <div class="d-flex align-items-center gap-1 mt-2 flex-wrap">
                        <i class="fa-regular fa-circle-check text-primary"></i>
                        <span>کوتاهی مو: </span>
                        <span class="text-primary">2 روز پیش</span>
                    </div>
                </div>
                <div class="custom-card-footer"><span></span></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-sm-12 col-md-8">
            <div class="col-lg-12 col-sm-12 mt-5">
                <div class="d-flex py-4 rounded bg-white flex-column justify-content-evenly shadow-md">
                    <div class="p-2 fw-bold text-center ">برای ثبت رزرو جدید مشتری، از این بخش استفاده کنید.</div>
                    <div class="p-2 text-secondary text-center">
                        <button class="btn btn-primary">ثبت رزرو جدید</button>
                    </div>
                </div>
            </div>
       <div class="mt-5">
           <div class="row">
               <div class="col-7 d-flex align-items-center">
                   <h4 class="fw-bold">نوبت های امروز</h4>
               </div>

               <div class="col-5">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                          <select class="js-example-basic-single halfSelectForm" name="state" id="changeStatus">
                              <option value="0"><?= __("all_status") ?></option>
                              <?php foreach ($visitStatus as $status):?>
                              <option value="<?= $status->id ?>" <?= ( $status->id == $getStatus) ? 'selected' : ''  ?>><?= $status->fa_title ?></option>
                              <?php endforeach ?>
                          </select>
                      <form method="get" class="d-flex align-items-center w-100">
                          <div class="input-group">
                              <input type="text" class="form-control border-end-0" name="search" id="search"
                                     value="<?= htmlspecialchars($search) ?>" placeholder="<?= __("search") ?>">
                              <?php if(empty($search)):?>
                                  <button class="btn btn-search border-start-0" type="submit" id="btn-search">
                                      <i class='fas fa-search'></i>
                                  </button>
                              <?php else:?>
                                  <?php if (!empty($search)): ?><button class="btn btn-search border-start-0" type="button" id="btn-delete">
                                      <a href="?page=1&per_page=<?= $per_page ?>" class="center text-decoration-none"><i class='fas fa-xmark text-primary'></i></a>
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
               <table class="table custom-table">
                   <thead class="table-primary ">
                   <tr>
                       <th> <input class="form-check-input checkBox" type="checkbox" value="" id="allServices">
                           <label for="allServices">ردیف</label>
                       </th>
                       <th>نام</th>
                       <th>نام خانوادگی</th>
                       <th>خدمات</th>
                       <th>وضعیت</th>
                       <th>عملیات</th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php $count = 1 ?>
                   <?php foreach ($visits as $item) :?>
                   <tr>
                       <td>
                           <div class="">
                               <input class="form-check-input checkBox" type="checkbox" value="" id="tableService<?= $count ?>">
                               <label class="form-check-label" for="tableService<?= $count ?>">
                                   <?= htmlspecialchars($count) ?>
                               </label>
                           </div>
                       </td>
                       <td> <?= htmlspecialchars( $item->customerName) ?></td>
                       <td><?= htmlspecialchars($item->customerLastName) ?></td>
                       <td><?= htmlspecialchars($item->service) ?></td>
                       <td><?= htmlspecialchars($item->visitStatus) ?></td>
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
                                       <li><a class="dropdown-item text-start" href="<?= BASE_URL ?>/admin/services/category/edit/<?= $item->id ?>" data-bs-toggle="modal" data-bs-target="#editModal">ویرایش</a></li>
                                       <li><hr class="dropdown-divider"></li>
                                       <li><a class="dropdown-item text-start">حذف</a></li>
                                   </ul>
                               </div>
                       </td>


                   </tr>
                   <?php $count ++; ?>
             <?php endforeach;?>
                   </tbody>

               </table>
           </div>
            <?php else:?>
                <div class="alert alert-danger">پیدا نشد</div>
            <?php endif;?>
           </div>
       </div>

            <div class="mt-5">
                <div class="card  shadow-md" >
                    <div class="card-header  fw-bold bg-transparent" style="--divider-offset: 1.25rem; padding: 27px;"><h4 class="fw-bold">گزارش روز گذشته</h4></div>
                    <div class="card-body" style="--divider-offset: 1.25rem; padding: 0 27px 0 27px ;">
                            <div class="my-2 text-start">
                                <i class="fa-regular fa-circle-check text-primary"></i>
                                <span>
                             <?php echo ($customersHasServiceCount == 0) ? __("customers_has_not_done_services") : str_replace('%s', $customersHasServiceCount, __("count_of_customers_had_services")); ?>
                                </span>
                            </div>
                            <div class="divider_lg"></div>
                        <div class="my-2 text-start">
                                <i class="fa-regular fa-circle-check text-primary"></i>
                            <?php echo ($cancelledReservesCount == 0) ? __("not_have_cancelled_reserves") : str_replace('%s', $cancelledReservesCount, __("cancelled_reserves")); ?>
                            </div>
                            <div class="divider_lg"></div>
                        <div class="my-2 text-start">
                                <i class="fa-regular fa-circle-check text-primary"></i>
                                <span>رضایت مشتریان: 92%</span>
                            </div>


                    </div>
                    <div class="text-center" style="padding: 0 27px 27px 0 ;">
                        <button class="btn btn-primary py-2 px-4">خروجی excel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-md-4">
            <div class="mt-5">
                <div class="shadow-md bg-white rounded py-4 px-3 tips ">
                    <h4 class="text-start pb-2">آخرین اطلاعیه ها</h4>
                    <ul class="list-unstyled d-flex flex-column align-items-end" style="direction: ltr; gap: 25px;">
                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">ضدعفونی ابزارها تا ساعت ۹ انجام شود</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>

                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">شروع ساعت کاری در تاریخ ۶ شهریور ساعت ۸:۰۰ میباشد</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>

                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">.موها را بعد از کراتین مرطوب نگه دارید</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>

                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">.مراقبت از ناخن‌ها بعد از مانیکور ضروری است</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>


                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">.موها را بعد از کراتین مرطوب نگه دارید</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>

                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">.مراقبت از ناخن‌ها بعد از مانیکور ضروری است</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>

                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">.موها را بعد از کراتین مرطوب نگه دارید</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>

                        <li class="d-flex align-items-center mb-2">
                            <span class="mx-2">.مراقبت از ناخن‌ها بعد از مانیکور ضروری است</span>
                            <i class="fa fa-circle text-primary ms-2"></i>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>


</div>
</div>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            minimumResultsForSearch: Infinity,
        });
        $('#changeStatus').on('change', function () {
            var getStatus = $(this).val();
            var url = new URL(window.location.href);
            if(getStatus !==0){
                url.searchParams.set('status', getStatus);
                window.location.href = url.toString();
            }
        });

    });

</script>