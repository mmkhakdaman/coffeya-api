<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::prefix('category')
    ->group(function () {
        Route::get('/list', [CategoryController::class, 'list']);
    });


Route::prefix('admin/category')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('/list', [CategoryController::class, 'adminList']);
        Route::post('/create', [CategoryController::class, 'create']);
        Route::put('/update/{category}', [CategoryController::class, 'update']);
        Route::put('/reorder', [CategoryController::class, 'reorder']);
    });
