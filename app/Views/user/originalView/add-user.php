<body style="background: #E9F3F9; padding: 30px 20px 30px 20px">
<div class="container-fluid">
  <div class="row">
      <div class="d-lg-none">
              <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
                  <i class="fa fa-bars"></i>
              </button>
      </div>

      <div class="col-lg-3 col-md-3 col-xl-3 col-xxl-2 pe-4 d-none d-lg-block">
          <div class="bg-white rounded-2 d-flex flex-column gap-5 pb-4 sidebar-full-height">
              <img src="<?= asset('img/delarose-black.png') ?>" style="height: 200px; object-fit: contain" class="mt-4">
              <nav class="topnav navbar navbar-light">

              <ul class="navbar-nav flex-fill w-100 mb-2 " style="margin: 0 0 0 0">
                  <li class="nav-item justify-content d-flex py-2 normal-menu-item">
                      <i class="fa fa-dashboard icon-color"></i>
                      <span class="item-text">داشبورد</span>
                  </li>
                  <li class="nav-item  dropdown ">
                      <a href="#dashboard"  data-bs-toggle="collapse" aria-expanded="false" class="justify-content d-flex dropdown-toggle  nav-link  right-border">
                          <i class="fa fa-user icon-color"></i>
                          <span class="ml-3 item-text px-2">مدیریت کاربران</span>
                      </a>
                      <ul class="collapse list-unstyled pl-4 w-100 show " id="dashboard">
                          <li class="nav-item  active">
                              <a class="nav-link  " href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                          </li>
                          <li class="nav-item ">
                              <a class="nav-link  " href="./dashboard-analytics.html"><span class="ml-1 under ">مدیریت کاربران</span></a>
                          </li>
                          <li class="nav-item m-0">
                              <a class="nav-link  " href="./dashboard-sales.html"><span class="ml-1 under ">تنظیمات</span></a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item dropdown ">
                      <a href="#manageService" data-bs-toggle="collapse" aria-expanded="false" class="justify-content d-flex dropdown-toggle nav-link ">
                          <i class="fa fa-user icon-color"></i>
                          <span class="ml-3 item-text px-2">مدیریت سرویس ها</span>
                      </a>
                      <ul class="collapse list-unstyled pl-4 w-100 " id="manageService">
                          <li class="nav-item active">
                              <a class="nav-link  " href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                          </li>
                          <li class="nav-item ">
                              <a class="nav-link  " href="./dashboard-analytics.html"><span class="ml-1 under">مدیریت سرویس ها</span></a>
                          </li>
                          <li class="nav-item m-0">
                              <a class="nav-link  " href="./dashboard-sales.html"><span class="ml-1 under ">تنظیمات</span></a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item  dropdown">
                      <a href="#reserveList" data-bs-toggle="collapse" aria-expanded="false" class="justify-content d-flex dropdown-toggle nav-link ">
                          <i class="fa fa-book icon-color"></i>
                          <span class="ml-3 item-text px-2">لیست رزروها</span>
                      </a>
                      <ul class="collapse list-unstyled pl-4 w-100 " id="reserveList">
                          <li class="nav-item  active">
                              <a class="nav-link  " href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                          </li>
                          <li class="nav-item ">
                              <a class="nav-link  " href="./dashboard-analytics.html"><span class="ml-1 under ">مدیریت رزرو ها</span></a>
                          </li>
                          <li class="nav-item m-0">
                              <a class="nav-link  " href="./dashboard-sales.html"><span class="ml-1 under">تنظیمات</span></a>
                          </li>
                      </ul>
                  </li>
                  <li class="nav-item justify-content  d-flex  py-2 normal-menu-item ">
                      <i class="fa fa-gear icon-color"></i>
                      <span class="item-text">تنظیمات</span>
                  </li>
                  <li class="nav-item justify-content d-flex  py-2 normal-menu-item">
                      <i class="fa-solid fa-compress icon-color"></i>
                      <span class="item-text">جمع کردن فهرست</span>
                  </li>
              </ul>

              </nav>
              <div class="justify-content d-flex nav-item py-2 normal-menu-item">
                  <i class="fa-solid fa-right-from-bracket icon-color"></i>
                  <span class="item-text">خروج</span>
              </div>
          </div>
      </div>
      <div class="offcanvas offcanvas-start p-0" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
          <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="sidebarLabel">منو</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body p-0">
              <nav class="topnav navbar navbar-light">

                  <ul class="navbar-nav flex-fill w-100 mb-2 " style="margin: 0 0 0 0">
                      <li class="nav-item justify-content d-flex py-2 normal-menu-item">
                          <i class="fa fa-dashboard icon-color"></i>
                          <span class="item-text">داشبورد</span>
                      </li>
                      <li class="nav-item  dropdown ">
                          <a href="#dashboard"  data-bs-toggle="collapse" aria-expanded="false" class="justify-content d-flex dropdown-toggle  nav-link  right-border">
                              <i class="fa fa-user icon-color"></i>
                              <span class="ml-3 item-text ">مدیریت کاربران</span>
                          </a>
                          <ul class="collapse list-unstyled pl-4 w-100 show " id="dashboard">
                              <li class="nav-item  active">
                                  <a class="nav-link  " href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                              </li>
                              <li class="nav-item ">
                                  <a class="nav-link  " href="./dashboard-analytics.html"><span class="ml-1 under ">مدیریت کاربران</span></a>
                              </li>
                              <li class="nav-item m-0">
                                  <a class="nav-link  " href="./dashboard-sales.html"><span class="ml-1 under ">تنظیمات</span></a>
                              </li>
                          </ul>
                      </li>
                      <li class="nav-item dropdown ">
                          <a href="#manageService" data-bs-toggle="collapse" aria-expanded="false" class="justify-content d-flex dropdown-toggle nav-link ">
                              <i class="fa fa-user icon-color"></i>
                              <span class="ml-3 item-text">مدیریت سرویس ها</span>
                          </a>
                          <ul class="collapse list-unstyled pl-4 w-100 " id="manageService">
                              <li class="nav-item active">
                                  <a class="nav-link  " href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                              </li>
                              <li class="nav-item ">
                                  <a class="nav-link  " href="./dashboard-analytics.html"><span class="ml-1 under">مدیریت سرویس ها</span></a>
                              </li>
                              <li class="nav-item m-0">
                                  <a class="nav-link  " href="./dashboard-sales.html"><span class="ml-1 under ">تنظیمات</span></a>
                              </li>
                          </ul>
                      </li>
                      <li class="nav-item  dropdown">
                          <a href="#reserveList" data-bs-toggle="collapse" aria-expanded="false" class="justify-content d-flex dropdown-toggle nav-link ">
                              <i class="fa fa-book icon-color"></i>
                              <span class="ml-3 item-text">لیست رزروها</span>
                          </a>
                          <ul class="collapse list-unstyled pl-4 w-100 " id="reserveList">
                              <li class="nav-item  active">
                                  <a class="nav-link  " href="./index.html"><span class="ml-1 under text-primary">افزودن</span></a>
                              </li>
                              <li class="nav-item ">
                                  <a class="nav-link  " href="./dashboard-analytics.html"><span class="ml-1 under ">مدیریت رزرو ها</span></a>
                              </li>
                              <li class="nav-item m-0">
                                  <a class="nav-link  " href="./dashboard-sales.html"><span class="ml-1 under">تنظیمات</span></a>
                              </li>
                          </ul>
                      </li>
                      <li class="nav-item justify-content  d-flex  py-2 normal-menu-item ">
                          <i class="fa fa-gear icon-color"></i>
                          <span class="item-text">تنظیمات</span>
                      </li>
                      <li class="nav-item justify-content d-flex  py-2 normal-menu-item">
                          <i class="fa-solid fa-compress icon-color"></i>
                          <span class="item-text">جمع کردن فهرست</span>
                      </li>
                  </ul>

              </nav>
              <div class="justify-content d-flex nav-item py-2 normal-menu-item">
                  <i class="fa-solid fa-right-from-bracket icon-color"></i>
                  <span class="item-text">خروج</span>
              </div>
          </div>
      </div>
      <div class="col-lg-9 col-md-12 col-xl-9 col-xxl-10 pe-3">
          <div class="row mt-lg-0 mt-3">
              <div class="col-12 d-flex  justify-content-between align-items-center">
                  <h4 class="right-border ps-2">افزودن کاربر</h4>
                 <div class="d-flex align-items-center">
                     <div class="d-flex justify-content-center " >
                         <div class="profile-details" style="width: 60px; height: 60px">
                             <i class="fa-solid fa-bell icon-color"></i>
                         </div>
                     </div>
                     <div class="dropdown ps-2">
                         <button class="btn btn-white py-3 rounded-4 dropdown-toggle d-flex justify-content" id="navbarDropdownMenuLink" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                             <span class="fa fa-user"></span>
                             <span class=" ps-3 pe-3">هانیه محمدپور</span>
                         </button>
                         <div class="dropdown-menu dropdown-menu-right " aria-labelledby="navbarDropdownMenuLink">
                             <a class="dropdown-item" href="#">Profile</a>
                             <hr class="dropdown-divider">
                             <a class="dropdown-item" href="#">Settings</a>
                             <hr class="dropdown-divider">
                             <a class="dropdown-item" href="#">Activities</a>
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
                      <label for="breaktime">زمان استراحت</label>
                      <select class="form-select"  id="breaktime">
                          <option value="1">12 و نیم الی 13 ونیم</option>
                          <option value="1">13 و نیم الی 14 ونیم</option>
                          <option value="1">14 و نیم الی 15 ونیم</option>
                      </select>
                  </div>
                  <div class="col-6">
                      <label for="user-role">مهارت<span class="bullet-color">*</span></label>
                      <div class="">
                          <select class="form-select" id="multiple-select-field"  multiple>
                              <option>Christmas Island</option>
                              <option>South Sudan</option>
                              <option>United States</option>
                              <option>Canada</option>
                              <option>Canad3a</option>
                              <option>Canada4</option>
                              <option>Canad5a</option>
                              <option>Canada6</option>
                              <option>Canada7</option>
                              <option>Canada8</option>
                              <option>Canada9</option>
                              <option>Canada00</option>
                              <option>Canada04</option>
                          </select>
                      </div>
                  </div>
              </div>

              <div class="mt-5 ">
                  <span>شیفت کاری</span>
                      <div class="table-wrapper mt-3">
                          <div class="table-responsive ">
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
                                      <td>
                                              <i class="fa-light fa-clock icon-color pe-1"></i>
                                              <span>8:00</span>
                                      </td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
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
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                      <td>
                                          <div class="d-flex justify-content-center">
                                              <div class="form-check">
                                                  <label class="form-check-label" for="flexCheckDefault2">
                                                      تعطیل
                                                  </label>
                                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>دوشنبه</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                      <td>
                                          <div class="d-flex justify-content-center">
                                              <div class="form-check">
                                                  <label class="form-check-label" for="flexCheckDefault3">
                                                      تعطیل
                                                  </label>
                                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>سه شنبه</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                      <td>
                                          <div class="d-flex justify-content-center">
                                              <div class="form-check">
                                                  <label class="form-check-label" for="flexCheckDefault4">
                                                      تعطیل
                                                  </label>
                                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault4">
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>چهارشنبه</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                      <td>
                                          <div class="d-flex justify-content-center">
                                              <div class="form-check">
                                                  <label class="form-check-label" for="flexCheckDefault5">
                                                      تعطیل
                                                  </label>
                                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault5">
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>پنج شنبه</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                      <td>
                                          <div class="d-flex justify-content-center">
                                              <div class="form-check">
                                                  <label class="form-check-label" for="flexCheckDefault6">
                                                      تعطیل
                                                  </label>
                                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault6">
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>جمعه</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>8:00</td>
                                      <td><i class="fa-light fa-clock icon-color pe-1"></i>17:00</td>
                                      <td>
                                          <div class="d-flex justify-content-center">
                                              <div class="form-check">
                                                  <label class="form-check-label" for="flexCheckDefault7">
                                                      تعطیل
                                                  </label>
                                                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault7">
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                                  </tbody>

                              </table>
                          </div>
                      </div>
                  <button class="btn btn-primary mt-5 px-5" >افزودن</button>
              </div>

</div>
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    $(document).ready(function () {
        $('#multiple-select-field').select2()
    })
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownLinks = document.querySelectorAll('.nav-item.dropdown > a.dropdown-toggle[href^="#"]');

        dropdownLinks.forEach(link => {
            const collapseId = link.getAttribute('href');
            const collapseEl = document.querySelector(collapseId);
            if (!collapseEl) return;

            // بررسی اگر از قبل باز است
            if (collapseEl.classList.contains('show')) {
                link.classList.add('right-border');
                link.setAttribute('aria-expanded', 'true');
            }

            collapseEl.addEventListener('show.bs.collapse', function () {
                // ابتدا همه را پاک کن
                dropdownLinks.forEach(l => l.classList.remove('right-border'));

                // به لینک جاری اضافه کن
                link.classList.add('right-border');
                link.setAttribute('aria-expanded', 'true');
            });

            collapseEl.addEventListener('hide.bs.collapse', function () {
                link.classList.remove('right-border');
                link.setAttribute('aria-expanded', 'false');
            });
        });
    });
</script>


