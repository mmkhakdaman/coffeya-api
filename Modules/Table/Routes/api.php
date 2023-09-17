<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Table\Http\Controllers\TableController;

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


Route::middleware('auth:api')
    ->prefix('admin')
    ->group(
        function () {
            Route::apiResource('table', TableController::class)->except(['destroy']);
            Route::put('table/{table}/toggle-active', [TableController::class, 'toggleActive'])->name('table.toggle-active');
        }
    );
