<?php

//return [
//    'GET' => [
//        '/' => [HomeController::class, 'index'],
//        '/user/login' => [UserController::class, 'login'],
//        '/user/logout' => [UserController::class, 'logout'],
//        '/user/register' => [UserController::class, 'register'],
//        '/home/index' => [HomeController::class, 'index'],
//        '/admin/panel' => [AdminController::class, 'panel'],
//        '/forbidden' => [ErrorController::class, 'forbidden'],
//    ],
//    'POST' => [
//        '/user/login' => [UserController::class, 'login'],
//        '/user/register' => [UserController::class, 'register'],
//    ]
//];

use App\Core\Route;
use App\Controllers\UserController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\ErrorController;

Route::get('/', [HomeController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/home/index', [HomeController::class, 'index']);
    Route::get('/user/profile', [UserController::class, 'show']);
    Route::get('/user/edit', [UserController::class, 'edit']);
    Route::post('/user/edit', [UserController::class, 'update']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile', [UserController::class, 'show']);
    Route::post('/user/profile', [UserController::class, 'update']);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/user/edit', [UserController::class, 'edit']);
    Route::post('/user/edit', [UserController::class, 'update']);
    Route::get('/user/show/{id}', [UserController::class, 'showProfile']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'usersList']);
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser']);
    Route::post('/admin/user/edit/{id}', [AdminController::class, 'updateUser']);
    Route::get('/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
});

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'panel']);
});

Route::get('/user/login', [UserController::class, 'login']);
Route::post('/user/login', [UserController::class, 'login']);
Route::get('/user/logout', [UserController::class, 'logout']);
Route::get('/forbidden', [ErrorController::class, 'forbidden']);
Route::get('/user/register', [UserController::class, 'register']);
Route::post('/user/register', [UserController::class, 'register']);


Route::get('/admin/users', [AdminController::class, 'usersList']);

Route::middleware(['guest'])->group(function () {
    Route::get('/user/register', [UserController::class, 'register']);
    Route::post('/user/register', [UserController::class, 'register']);
    Route::get('/user/login', [UserController::class, 'login']);
    Route::post('/user/login', [UserController::class, 'login']);
});