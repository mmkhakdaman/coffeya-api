<?php


use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\DiscountController;


Route::prefix('payment')
    ->middleware('auth:tenant_admin')
    ->group(function () {
        Route::get('/discounts', [DiscountController::class, 'index']);
        Route::post('/discounts', [DiscountController::class, 'store']);
        Route::get('/discounts/{discount}', [DiscountController::class, 'show']);
        Route::put('/discounts/{discount}', [DiscountController::class, 'update']);
        Route::delete('/discounts/{discount}', [DiscountController::class, 'destroy']);
    });
