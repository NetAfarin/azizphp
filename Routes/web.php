<?php
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\AdminController;
use App\Controllers\ErrorController;
use App\Core\Route;
use App\Middlewares\Auth;
use App\Middlewares\Guest;
use App\Middlewares\RegisterValidationMiddleware;
use App\Middlewares\Role;

Route::get('/', [HomeController::class, 'index']);

Route::middleware([Guest::class])->group(function () {
    Route::get('/user/login', [UserController::class, 'login']);
    Route::post('/user/login', [UserController::class, 'login']);
});

Route::middleware([Guest::class, RegisterValidationMiddleware::class])->group(function () {
    Route::get('/user/register', [UserController::class, 'register']);
    Route::post('/user/register', [UserController::class, 'register']);
});

Route::middleware([Auth::class])->group(function () {
    Route::get('/home/index', [HomeController::class, 'index']);
    Route::get('/user/profile', [UserController::class, 'profile']);
//    Route::get('/user/edit', [UserController::class, 'edit']);
//    Route::post('/user/edit', [UserController::class, 'update']);
    Route::get('/user/logout', [UserController::class, 'logout']);
    Route::get('/user/show/{id}', [UserController::class, 'showProfile']);
});

Route::middleware([Auth::class, Role::class])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'usersList']);
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser']);
    Route::post('/admin/user/edit/{id}', [AdminController::class, 'updateUser']);
    Route::get('/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
});

Route::middleware([Auth::class, Role::class])->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'panel']);
});

Route::get('/forbidden', [ErrorController::class, 'forbidden']);

Route::get('/home', [HomeController::class, 'index']);
Route::middleware([Auth::class])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard']);
});