<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چیدمان عمودی دو ستون</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?= asset('css/theme.css')?>" rel="stylesheet" />
</head>
<body style="background: #E9F3F9; padding: 30px 20px 30px 20px">
<div class="container-fluid">
  <div class="row">
      <div class="col-2 pe-4">
          <div class="bg-white rounded-2 d-flex flex-column gap-3 pb-4 h-100">
              <img src="<?= asset('img/logo.jpeg') ?>" style="height: 200px; object-fit: contain" class="mt-4">

              <ul class="navbar-nav flex-fill w-100 mb-2 " style="margin: 0 0 0 0">
                  <li class="nav-item pr-16">
                      <i class="fa fa-dashboard icon-color"></i>
                      <span class="item-text">داشبورد</span>
                  </li>
                  <li class="nav-item dropdown ">
                      <a href="#dashboard"  data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link active-menu right-border">
                          <i class="fa fa-user icon-color"></i>
                          <span class="ml-3 item-text ">مدیریت کاربران</span>
                      </a>
                      <ul class=" list-unstyled pl-4 w-100 pr-16" id="dashboard">
                          <li class="nav-item active">
                              <a class="nav-link pl-3" href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link pl-3" href="./dashboard-analytics.html"><span class="ml-1 under text-black">مدیریت کاربران</span></a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link pl-3" href="./dashboard-sales.html"><span class="ml-1 under text-black">تنظیمات</span></a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item dropdown ">
                      <a href="#manageService" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link pr-16">
                          <i class="fa fa-user icon-color"></i>
                          <span class="ml-3 item-text">مدیریت سرویس ها</span>
                      </a>
                      <ul class="collapse list-unstyled pl-4 w-100 pr-16" id="manageService">
                          <li class="nav-item active">
                              <a class="nav-link pl-3" href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link pl-3" href="./dashboard-analytics.html"><span class="ml-1 under text-black">مدیریت سرویس ها</span></a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link pl-3" href="./dashboard-sales.html"><span class="ml-1 under text-black">تنظیمات</span></a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item dropdown">
                      <a href="#reserveList" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link pr-16">
                          <i class="fa fa-book icon-color"></i>
                          <span class="ml-3 item-text">لیست رزروها</span>
                      </a>
                      <ul class="collapse list-unstyled pl-4 w-100 pr-16" id="reserveList">
                          <li class="nav-item active">
                              <a class="nav-link pl-3" href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link pl-3" href="./dashboard-analytics.html"><span class="ml-1 under text-black">مدیریت رزرو ها</span></a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link pl-3" href="./dashboard-sales.html"><span class="ml-1 under text-black">تنظیمات</span></a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item pr-16">
                      <i class="fa fa-gear icon-color"></i>
                      <span class="item-text">تنظیمات</span>
                  </li>
                  <li class="nav-item pr-16">
                      <i class="fa-solid fa-compress icon-color"></i>
                      <span class="item-text">جمع کردن فهرست</span>
                  </li>
              </ul>

              <div class="pr-16">
                  <i class="fa-solid fa-right-from-bracket icon-color"></i>
              <span class="item-text">خروج</span>
              </div>
          </div>
      </div>
      <div class="col-10 pe-4">
          <div class="row">
              <div class="col-6 d-flex  align-items-center">
                  <h4 class="right-border">افزودن کاربر</h4>
              </div>
              <div class="col-6 d-flex justify-content-end p-0">
                  <div class="d-flex justify-content-center " >
                      <div class="profile-details" style="width: 60px; height: 60px">
                          <i class="fa-solid fa-bell icon-color"></i>
                      </div>
                  </div>
                  <div class="d-flex justify-content-center ">
                      <div class="profile-details p-4">
                              <span class=" pe-2">
                                  <i class="fa-solid fa-user icon-color" ></i>
                              </span>
                          <div class="pe-2">هانیه محمدپور</div>
                          <i class="fa-solid fa-caret-down icon-color"></i>
                      </div>
                  </div>
              </div>
          </div>
              <div class="mt-5"><h4>اطلاعات پایه</h4></div>
              <div class="row mt-5">
              <div class="col-6">
                  <label for="username">نام <span class="bullet-color">*</span></label>
                  <input type="text" class="form-control" id="username">
              </div>
              <div class="col-6">
                  <label for="lastname">نام خانوادگی<span class="bullet-color">*</span></label>
                  <input type="text" class="form-control" id="lastname">
              </div>
              </div>
              <div class="row mt-5">
              <div class="col-6">
                  <label for="national-code">کد ملی<span class="bullet-color">*</span></label>
                  <input type="text" class="form-control ltr-input" id="national-code">
              </div>
              <div class="col-6">
                  <label for="birthday-date">تاریخ تولد<span class="bullet-color">*</span></label>
                  <input type="text" class="form-control" id="birthday-date">
              </div>
              </div>
              <div class="row mt-5">
              <div class="col-6">
                  <label for="phone-number">شماره همراه<span class="bullet-color">*</span></label>
                  <input type="text" class="form-control ltr-input" id="phone-number">
              </div>
              <div class="col-6">
                  <label for="user-role">نقش کاربر<span class="bullet-color">*</span></label>
                  <input type="text" class="form-control" id="user-role">
              </div>
                  </div>
              <div class=" mt-5">
          <label for="user-role">آدرس محل سکونت<span class="bullet-color">*</span></label>
          <input type="text" class="form-control" id="user-role">
              </div>
              <hr class="mt-5">
              <div class="mt-5"><h4>اطلاعات شغلی</h4></div>
              <div class="row mt-5">
                  <div class="col-6">
                      <label for="phone-number">زمان استراحت</label>
                      <input type="text" class="form-control ltr-input" id="phone-number">
                  </div>
                  <div class="col-6">
                      <label for="user-role">مهارت<span class="bullet-color">*</span></label>
                      <input type="text" class="form-control" id="user-role">
                  </div>
              </div>

              <div class="mt-5 ">
                  <span>شیفت کاری</span>
                      <div class="table-wrapper mt-3">
                          <table class="table custom-table">

                              <thead class="table-primary ">
                              <tr>
                                  <th>روز</th>
                                  <th>شروع کار</th>
                                  <th>پایان کار</th>
                                  <th>وضعیت</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr>
                                  <td>شنبه</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 8:00</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 17:00</td>
                                  <td>
                                      <div class="d-flex justify-content-center">
                                          <div class="form-check">
                                              <label class="form-check-label" for="flexCheckDefault">
                                                  تعطیل
                                              </label>
                                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                          </div>
                                      </div>

                                  </td>

                              </tr>
                              <tr>
                                  <td>یکشنبه</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 8:00</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 17:00</td>
                                  <td>
                                      <div class="d-flex justify-content-center">
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                              <label class="form-check-label" for="flexCheckDefault">
                                                  تعطیل
                                              </label>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td>دوشنبه</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 8:00</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 17:00</td>
                                  <td>
                                      <div class="d-flex justify-content-center">
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                              <label class="form-check-label" for="flexCheckDefault">
                                                  تعطیل
                                              </label>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td>سه شنبه</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 8:00</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 17:00</td>
                                  <td>
                                      <div class="d-flex justify-content-center">
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                              <label class="form-check-label" for="flexCheckDefault">
                                                  تعطیل
                                              </label>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td>چهارشنبه</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 8:00</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 17:00</td>
                                  <td>
                                      <div class="d-flex justify-content-center">
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                              <label class="form-check-label" for="flexCheckDefault">
                                                  تعطیل
                                              </label>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td>پنج شنبه</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 8:00</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 17:00</td>
                                  <td>
                                      <div class="d-flex justify-content-center">
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                              <label class="form-check-label" for="flexCheckDefault">
                                                  تعطیل
                                              </label>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td>جمعه</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 8:00</td>
                                  <td><i class="fa-regular fa-clock icon-color"></i> 17:00</td>
                                  <td>
                                      <div class="d-flex justify-content-center">
                                          <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                              <label class="form-check-label" for="flexCheckDefault">
                                                  تعطیل
                                              </label>
                                          </div>
                                      </div>
                                  </td>
                              </tr>
                              </tbody>

                          </table>
                      </div>
                  <button class="btn btn-primary mt-5 px-5" >افزودن</button>
              </div>

</div>
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
