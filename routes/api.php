<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ProtectedRouteAuth;
use App\Http\Middleware\RespondWithJson;
use Illuminate\Support\Facades\Route;

Route::middleware(RespondWithJson::class)->group(function () {
    Route::apiResource('/login', AuthController::class);
    Route::apiResource('/users', UserController::class)->only('store');

    Route::middleware(ProtectedRouteAuth::class)->group(function () {
        Route::apiResource('/users', UserController::class)->except('store');
        Route::apiResource('/books', BookController::class);
    });
});
