<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

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


Route::prefix('product')
    ->group(function () {
        Route::get('/list', [ProductController::class, 'activeProductList']);
    });

Route::prefix('/admin/product')
    ->group(function () {
        Route::get('/list', [ProductController::class, 'list']);
        Route::post('/create', [ProductController::class, 'create']);
        Route::put('/update/{product}', [ProductController::class, 'update']);
        Route::put('/reorder', [ProductController::class, 'reorder']);
        Route::put('/toggle-active/{product}', [ProductController::class, 'toggleActive']);
        Route::put('/toggle-stock/{product}', [ProductController::class, 'toggleStock']);
        Route::delete('/delete/{product}', [ProductController::class, 'delete']);
    });
