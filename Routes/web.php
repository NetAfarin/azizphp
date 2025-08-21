<?php

use App\Controllers\AdminBookingController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AdminController;
use App\Controllers\ErrorController;
use App\Core\Route;
use App\Middlewares\RateLimiterMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\RegisterValidationMiddleware;
use App\Middlewares\RoleMiddleware;

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);

Route::middleware([GuestMiddleware::class])->group(function () {
    Route::get('/user/login', [UserController::class, 'login']);
    Route::post('/user/login', [UserController::class, 'login']);
});

Route::middleware([GuestMiddleware::class, RegisterValidationMiddleware::class])->group(function () {
    Route::get('/user/register', [UserController::class, 'register']);
    Route::post('/user/register', [UserController::class, 'register']);
});

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
//    Route::get('/user/edit', [UserController::class, 'edit']);
//    Route::post('/user/edit', [UserController::class, 'update']);
    Route::get('/user/logout', [UserController::class, 'logout']);
    Route::get('/user/show/{id}', [UserController::class, 'showProfile']);
});

Route::middleware([AuthMiddleware::class, RoleMiddleware::class, RateLimiterMiddleware::class])->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'panel']);

    Route::get('/admin/users', [AdminController::class, 'usersList']);
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser']);
    Route::post('/admin/user/edit/{id}', [AdminController::class, 'updateUser']);
    Route::get('/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
//    Route::get('/admin/bookings', [AdminController::class, 'index']);
//    Route::get('/admin/booking/new', [AdminController::class, 'newBooking']);
//    Route::post('/admin/booking/save', [AdminController::class, 'saveBooking']);
    Route::get('/admin/bookings', [AdminBookingController::class, 'index']);
    Route::get('/admin/bookings/new', [AdminBookingController::class, 'create']);
    Route::post('/admin/bookings/store', [AdminBookingController::class, 'store']);
    Route::get('/admin/booking/getServices/{employeeId}', [AdminController::class, 'getEmployeeServices']);
    Route::get('/admin/booking/getServiceDuration/{employeeId}/{serviceId}', [AdminController::class, 'getServiceDuration']);
});


Route::get('/forbidden', [ErrorController::class, 'forbidden']);

Route::get('/admin/bookings/employee-services/{employeeId}', [AdminBookingController::class, 'getEmployeeServices']);
Route::get('/admin/bookings/service-duration/{employeeId}/{serviceId}', [AdminBookingController::class, 'getServiceDuration']);
