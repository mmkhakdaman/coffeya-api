<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('tenant')
    ->group(function () {
//        Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
//        Route::post('/auth/verify', [AuthController::class, 'verifyOtp']);
    });
