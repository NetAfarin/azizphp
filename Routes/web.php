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

Route::middleware([InstanceMiddleware::class])->group(function () {

//    Route::get('/{SALON_ID}/user/login', [UserController::class, 'login']);
    Route::middleware([GuestMiddleware::class])->group(function () {
        Route::get('/{SALON_ID}/user/login', [UserController::class, 'login']);
    });
//Route::middleware([GuestMiddleware::class])->group(function () {
//    Route::get('/user/login', [UserController::class, 'login']);
//    Route::get('/user/register', [UserController::class, 'register']);
//});

Route::middleware([GuestMiddleware::class, CsrfMiddleware::class, RateLimiterMiddleware::class])->group(function () {
    Route::post('/{SALON_ID}/user/login', [UserController::class, 'login']);
    Route::post('/{SALON_ID}/user/register', [UserController::class, 'register']);
});

// User routes
Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/{SALON_ID}/', [HomeController::class, 'index']);
    Route::get('/{SALON_ID}/home', [HomeController::class, 'index']);
    Route::get('/{SALON_ID}/user/profile', [UserController::class, 'profile']);
    Route::get('/{SALON_ID}/user/logout', [UserController::class, 'logout']);
    Route::get('/{SALON_ID}/user/show/{id}', [UserController::class, 'showProfile']);
});

// Admin POST routes (CSRF + RateLimit)
Route::middleware([AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class, RateLimiterMiddleware::class])->group(function () {
    Route::post('/{SALON_ID}/admin/user/edit/{id}', [AdminController::class, 'updateUser']);
    Route::post('/{SALON_ID}/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/{SALON_ID}/admin/bookings/store', [AdminBookingController::class, 'store']);
    Route::post('/{SALON_ID}/admin/services/category/create', [ServiceController::class, 'addCategory']);
    Route::post('/{SALON_ID}/admin/services/category/edit/{id}', [ServiceController::class, 'editCategory']);
    Route::post('/{SALON_ID}/admin/services/category/delete/{id}', [ServiceController::class, 'deleteCategory']);
    Route::post('/{SALON_ID}/admin/services/create', [ServiceController::class, 'addService']);
    Route::post('/{SALON_ID}/admin/services/delete/{id}', [ServiceController::class, 'deleteService']);
    Route::post('/{SALON_ID}/admin/services/edit/{id}', [ServiceController::class, 'editService']);
});

// Admin GET routes
Route::middleware([AuthMiddleware::class, RoleMiddleware::class])->group(function () {
    Route::get('/{SALON_ID}/admin/panel', [AdminController::class, 'panel']);
    Route::get('/{SALON_ID}/admin/users', [AdminController::class, 'usersList']);
    Route::get('/{SALON_ID}/admin/user/edit/{id}', [AdminController::class, 'editUser']);
    Route::get('/{SALON_ID}/admin/bookings', [AdminBookingController::class, 'index']);
    Route::get('/{SALON_ID}/admin/booking/getServices/{employeeId}', [AdminBookingController::class, 'getEmployeeServices']);
    Route::get('/{SALON_ID}/admin/booking/getServiceDuration/{employeeId}/{serviceId}', [AdminBookingController::class, 'getServiceDuration']);
    Route::get('/{SALON_ID}/admin/services/management', [ServiceController::class, 'management']);
    Route::get('/{SALON_ID}/admin/services/categories', [ServiceController::class, 'categories']);
    Route::get('/{SALON_ID}/admin/services', [ServiceController::class, 'services']);
    Route::get('/{SALON_ID}/admin/bookings/new', [AdminBookingController::class, 'create']);
    Route::get('/{SALON_ID}/admin/services/category/create', [ServiceController::class, 'addCategory']);
    Route::get('/{SALON_ID}/admin/services/category/edit/{id}', [ServiceController::class, 'editCategory']);
    Route::get('/{SALON_ID}/admin/services/create', [ServiceController::class, 'addService']);
    Route::get('/{SALON_ID}/admin/services/edit/{id}', [ServiceController::class, 'editService']);
    Route::get('/{SALON_ID}/user/register', [UserController::class, 'register']);
});

// Admin GET routes
Route::middleware([AuthMiddleware::class, SaRoleMiddleware::class])->group(function () {
    Route::get('/sa/panel', [SuperAdminController::class, 'panel']);
    Route::get('/sa/users', [SuperAdminController::class, 'userList']);
    Route::get('/sa/user/edit/{id}', [SuperAdminController::class, 'editUser']);
    Route::get('/sa/salons', [SuperAdminController::class, 'salonsList']);
    Route::get('/sa/salons/edit/{id}', [SuperAdminController::class, 'editSalon']);
});

});

// Errors
Route::get('/forbidden', [ErrorController::class, 'forbidden']);
