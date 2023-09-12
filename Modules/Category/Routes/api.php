<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::prefix('category')
    ->group(function () {
        Route::get('/list', [CategoryController::class, 'list']);
    });
