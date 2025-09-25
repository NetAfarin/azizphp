<?php

use App\Core\Route;

//Route::prefix('/api/v1')->group(function () {
//    Route::post('/login', [ApiAuthController::class, 'login']);
//    Route::middleware([ApiTokenMiddleware::class])->group(function () {
//        Route::get('/profile', [ApiUserController::class, 'profile']);
//        Route::get('/services', [ApiServiceController::class, 'index']);
//    });
//});
//
//Route::middleware([RateLimiterMiddleware::class])->group(function () {
//    Route::get('/api/v1/test', function () {
//        return json_response(['message' => 'Allowed']);
//    });
//});
//
//
//Route::middleware([ApiTokenMiddleware::class, RateLimiterMiddleware::class])->group(function () {
//    Route::get('/api/v1/profile', [ApiUserController::class, 'profile']);
//});