<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

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

Route::middleware('auth:customer')
    ->group(
        function () {
            Route::post('order/check-out', [OrderController::class, 'checkOut']);
        }
    );

Route::middleware('auth:tenant_admin')
    ->prefix('admin')
    ->group(
        function () {
            Route::get('/orders', [OrderController::class, 'index']);
            Route::get('/orders/{order}', [OrderController::class, 'show']);
            Route::put('/orders/{order}', [OrderController::class, 'update']);
        }
    );

