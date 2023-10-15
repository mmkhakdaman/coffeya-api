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

Route::middleware('auth:admin')
    ->group(
        function () {
//            Route::get('order', [OrderController::class, 'index']);
//            Route::get('order/{id}', [OrderController::class, 'show']);
//            Route::post('order', [OrderController::class, 'store']);
//            Route::put('order/{id}', [OrderController::class, 'update']);
//            Route::delete('order/{id}', [OrderController::class, 'destroy']);
        }
    );

