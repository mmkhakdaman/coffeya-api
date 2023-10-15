<?php


use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AuthController;

Route::prefix('admin')
    ->group(function () {
        Route::post('auth/login', [AuthController::class, 'login'])->middleware('guest:tenant_admin');
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('auth:tenant_admin');
    });
