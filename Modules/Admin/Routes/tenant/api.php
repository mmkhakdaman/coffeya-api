<?php


use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AuthController;

Route::prefix('admin')
    ->group(function () {
        Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/auth/verify', [AuthController::class, 'verifyOtp']);
    });
