<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ProtectedRouteAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/login', AuthController::class);
Route::apiResource('/users', UserController::class)->only('store');

Route::middleware(ProtectedRouteAuth::class)->group(function () {
    Route::apiResource('/users', UserController::class)->except('store');
});
