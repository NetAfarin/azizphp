<?php

use App\Controllers\AdminBookingController;
use App\Controllers\UserController;
use App\Controllers\AdminController;
use App\Controllers\ErrorController;
use App\Core\Route;
use App\Middlewares\RateLimiterMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\RoleMiddleware;
use App\Middlewares\CsrfMiddleware;

Route::middleware([GuestMiddleware::class, CsrfMiddleware::class, RateLimiterMiddleware::class])->group(function () {
    Route::get('/user/login', [UserController::class, 'login']);
    Route::post('/user/login', [UserController::class, 'login']);

    Route::get('/user/register', [UserController::class, 'register']);
    Route::post('/user/register', [UserController::class, 'register']);
});

// User routes
Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::get('/user/logout', [UserController::class, 'logout']);
    Route::get('/user/show/{id}', [UserController::class, 'showProfile']);
});

// Admin POST routes (CSRF + RateLimit)
Route::middleware([AuthMiddleware::class, RoleMiddleware::class, CsrfMiddleware::class, RateLimiterMiddleware::class])->group(function () {
    Route::post('/admin/user/edit/{id}', [AdminController::class, 'updateUser']);
    Route::post('/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
    Route::post('/admin/bookings/store', [AdminBookingController::class, 'store']);
});

// Admin GET routes
Route::middleware([AuthMiddleware::class, RoleMiddleware::class])->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'panel']);
    Route::get('/admin/users', [AdminController::class, 'usersList']);
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser']);
    Route::get('/admin/bookings', [AdminBookingController::class, 'index']);
    Route::get('/admin/bookings/new', [AdminBookingController::class, 'create']);
    Route::get('/admin/booking/getServices/{employeeId}', [AdminController::class, 'getEmployeeServices']);
    Route::get('/admin/booking/getServiceDuration/{employeeId}/{serviceId}', [AdminController::class, 'getServiceDuration']);
});

// Errors
Route::get('/forbidden', [ErrorController::class, 'forbidden']);
