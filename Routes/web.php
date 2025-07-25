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

Route::middleware(['guest'])->group(function () {
    Route::get('/user/register', [UserController::class, 'register']);
    Route::post('/user/register', [UserController::class, 'register']);
    Route::get('/user/login', [UserController::class, 'login']);
    Route::post('/user/login', [UserController::class, 'login']);
});