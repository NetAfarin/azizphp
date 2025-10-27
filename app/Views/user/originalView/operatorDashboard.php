<?php include BASE_PATH . '/app/Views/components/layout.php'; ?>

<div class="col mt-5">
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-md-4">
            <div class="card  shadow-md h-100 " >
                <div class="card-header text-center fw-bold bg-transparent">نوبت های امروز</div>
                <div class="card-body">
                    <p class="card-text flex-wrap d-flex align-items-center justify-content-center gap-3"><span class="text-primary" style="font-size: 45px">2 </span>نوبت فعال</p>
                </div>
                <div class="custom-card-footer"><span></span></div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-md-4 mt-3 mt-lg-0  mt-md-0">
            <div class="card  shadow-md" >
                <div class="card-header text-center fw-bold bg-transparent">خدمات انجام شده امروز</div>
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
                <div class="card-header text-center fw-bold bg-transparent">مشتری های حاضر</div>
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
                   <span class="fw-bold">نوبت های امروز</span>
               </div>

               <div class="col-5">
                  <div class="d-flex align-items-center justify-content-center gap-2">
                          <select class="js-example-basic-single " name="state" id="mySelect2">
                              <option><?= __("group_work") ?></option>
                              <option><?= __("group_work") ?></option>
                              <option><?= __("group_work") ?></option>
                          </select>
                      <div class="input-group">
                      <input type="text" class="form-control border-end-0 " name="search" id="search"
                                 value="<?php /*= htmlspecialchars($search) */?>" placeholder="<?= __("search") ?>">
                          <button
                                  class="btn btn-search border-start-0"
                                  type="submit" id="btn-search">
                              <i class="fa fa-search"></i>
                          </button>
                      </div>
                  </div>
               </div>
           </div>
           <div class="table-wrapper mt-4 ">
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
                                       <li><a class="dropdown-item text-start" href="<?= BASE_URL ?>/admin/services/category/edit/<?= $service->id ?>" data-bs-toggle="modal" data-bs-target="#editModal">ویرایش</a></li>
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
    });
</script>