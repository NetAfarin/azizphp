<?php

use App\Controllers\AdminBookingController;
use App\Controllers\ServiceController;
use App\Controllers\UserController;
use App\Controllers\AdminController;
use App\Controllers\ErrorController;
use App\Core\Route;
use App\Middlewares\RateLimiterMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\RoleMiddleware;
use App\Middlewares\CsrfMiddleware;

Route::middleware([GuestMiddleware::class])->group(function () {
    Route::get('/user/login', [UserController::class, 'login']);
    Route::get('/user/register', [UserController::class, 'register']);
});

Route::middleware([GuestMiddleware::class, CsrfMiddleware::class, RateLimiterMiddleware::class])->group(function () {
    Route::post('/user/login', [UserController::class, 'login']);
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
    Route::post('/admin/services/category/create', [ServiceController::class, 'addCategory']);
    Route::post('/admin/services/category/edit/{id}', [ServiceController::class, 'editCategory']);
    Route::post('/admin/services/category/delete/{id}', [ServiceController::class, 'deleteCategory']);
    Route::post('/admin/services/create', [ServiceController::class, 'addService']);
    Route::post('/admin/services/delete/{id}', [ServiceController::class, 'deleteService']);
    Route::post('/admin/services/edit/{id}', [ServiceController::class, 'editService']);
});

// Admin GET routes
Route::middleware([AuthMiddleware::class, RoleMiddleware::class])->group(function () {
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
    Route::get('/admin/services/category/create', [ServiceController::class, 'addCategory']);
    Route::get('/admin/services/category/edit/{id}', [ServiceController::class, 'editCategory']);
    Route::get('/admin/services/create', [ServiceController::class, 'addService']);
    Route::get('/admin/services/edit/{id}', [ServiceController::class, 'editService']);
});


// Errors
Route::get('/forbidden', [ErrorController::class, 'forbidden']);
