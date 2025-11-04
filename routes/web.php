<?php

use App\Controllers\AdminBookingController;
use App\Controllers\HomeController;
use App\Controllers\ServiceController;
use App\Controllers\SuperAdminController;
use App\Controllers\UserController;
use App\Controllers\AdminController;
use App\Controllers\ErrorController;
use App\Core\Route;
use App\Middlewares\InstanceMiddleware;
use App\Middlewares\RateLimiterMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\RoleMiddleware;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\SaRoleMiddleware;

//Route::middleware([InstanceMiddleware::class])->group(function () {

//    Route::get('/user/login', [UserController::class, 'login']);
    Route::middleware([InstanceMiddleware::class,GuestMiddleware::class])->group(function () {
        Route::get('/user/register', [UserController::class, 'register']);
        Route::get('/user/login', [UserController::class, 'login']);
        Route::get('/user/login2', [UserController::class, 'loginPage']);
        Route::get('/user/register2', [UserController::class, 'registerPage']);
        Route::get('/user/otp', [UserController::class, 'otpPage']);
        Route::get('/user/add', [UserController::class, 'add']);
        Route::get('/user/dashboard', [UserController::class, 'dashboard']);
        Route::get('/operator/dashboard', [UserController::class, 'operatorDashboard']);
        Route::get('/', [HomeController::class, 'index']);
    });
//Route::middleware([GuestMiddleware::class])->group(function () {
//    Route::get('/user/login', [UserController::class, 'login']);
//    Route::get('/user/register', [UserController::class, 'register']);
//});

Route::middleware([InstanceMiddleware::class,GuestMiddleware::class, CsrfMiddleware::class])->group(function () {
    Route::post('/user/login', [UserController::class, 'login']);
    Route::post('/user/login2', [UserController::class, 'loginPage']);
    Route::post('/user/register', [UserController::class, 'register']);
    Route::post('/user/register2', [UserController::class, 'registerPage']);
    Route::post('/user/add', [UserController::class, 'add']);
    Route::post('/user/otp', [UserController::class, 'otpPage']);
});

// User routes
Route::middleware([InstanceMiddleware::class,AuthMiddleware::class])->group(function () {
//    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::get('/user/logout', [UserController::class, 'logout']);
    Route::get('/user/show/{id}', [UserController::class, 'showProfile']);
    Route::get('/user/reserve', [UserController::class, 'reserveList']);
});

// Admin POST routes (CSRF + RateLimit)
Route::middleware([InstanceMiddleware::class,AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class, /*RateLimiterMiddleware::class*/])->group(function () {
    Route::post('/admin/user/edit/{id}', [AdminController::class, 'updateUser']);
    Route::post('/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/user/register/{userType}', [AdminController::class, 'addUser']);
    Route::post('/admin/bookings/store', [AdminBookingController::class, 'store']);
    Route::post('/admin/services/category/create', [ServiceController::class, 'addCategory']);
    Route::post('/admin/services/category/edit/{id}', [ServiceController::class, 'editCategory']);
    Route::post('/admin/services/category/delete/{id}', [ServiceController::class, 'deleteCategory']);
    Route::post('/admin/services/create', [ServiceController::class, 'addService']);
    Route::post('/admin/services/delete/{id}', [ServiceController::class, 'deleteService']);
    Route::post('/admin/services/update/{id}', [ServiceController::class, 'updateService']);
    Route::post('/admin/user/update/{id}', [UserController::class, 'updateUser2']);
    Route::post('/admin/services/edit/{id}', [ServiceController::class, 'editService']);
    Route::post('/admin/salon/create', [SuperAdminController::class, 'createSalon']);
    Route::post('/admin/salon/edit/{id}', [SuperAdminController::class, 'updateSalon']);
    Route::post('/admin/bookings/set/{employeeId}', [AdminController::class, 'bookingsSet']);
    Route::post('/sa/salon/create', [SuperAdminController::class, 'createSalon']);


});

// Admin GET routes
Route::middleware([InstanceMiddleware::class,AuthMiddleware::class, RoleMiddleware::class])->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'panel']);
    Route::get('/admin/users', [AdminController::class, 'usersList']);
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser']);
    Route::get('/admin/bookings', [AdminBookingController::class, 'index']);
    Route::get('/admin/booking/getServices/{employeeId}', [AdminBookingController::class, 'getEmployeeServices']);
    Route::get('/admin/booking/getServiceDuration/{employeeId}/{serviceId}', [AdminBookingController::class, 'getServiceDuration']);
    Route::get('/admin/services/management', [ServiceController::class, 'management']);
    Route::get('/admin/services/categories', [ServiceController::class, 'categories']);
    Route::get('/admin/services', [ServiceController::class, 'services']);
    Route::get('/admin/bookings/new', [AdminBookingController::class, 'create']);
    Route::get('/admin/bookings/create', [AdminBookingController::class, 'createReserve']);
    Route::get('/admin/employee/{employeeId}/schedule', [AdminBookingController::class, 'weeklySchedule']);
    Route::get('/admin/services/category/create', [ServiceController::class, 'addCategory']);
    Route::get('/admin/services/category/edit/{id}', [ServiceController::class, 'editCategory']);
    Route::get('/admin/services/create', [ServiceController::class, 'addService']);
    Route::get('/admin/services/getService/{id}', [ServiceController::class, 'getService']);
    Route::get('/admin/user/getUserData/{id}', [UserController::class, 'getUserData']);
    Route::get('/admin/services/edit/{id}', [ServiceController::class, 'editService']);
    Route::get('/admin/user/register/{userType}', [adminController::class, 'addUser']);
    Route::get('/admin/components', [AdminController::class, 'components']);
    Route::get('/admin/bookings/settings', [AdminController::class, 'bookingSettings']);
    Route::get('/admin/bookings/set/{employeeId}', [AdminController::class, 'bookingsSet']);
    Route::get('/admin/user/manage', [UserController::class, 'manageUsers']);

});

// Admin GET routes
Route::middleware([InstanceMiddleware::class,AuthMiddleware::class, SaRoleMiddleware::class])->group(function () {
    Route::get('/sa/dashboard', [SuperAdminController::class, 'panel']);
    Route::get('/sa/users', [SuperAdminController::class, 'userList']);
    Route::get('/sa/user/edit/{id}', [SuperAdminController::class, 'editUser']);
    Route::get('/sa/salons', [SuperAdminController::class, 'salonList']);
    Route::get('/sa/tickets', [SuperAdminController::class, 'tickets']);
    Route::get('/sa/salon/create', [SuperAdminController::class, 'createSalon']);
    Route::get('/sa/salon/edit/{id}', [SuperAdminController::class, 'editSalon']);

});

//});

// Errors
Route::get('/forbidden', [ErrorController::class, 'forbidden']);
//Route::get('/404', [ErrorController::class, 'notFound']);
