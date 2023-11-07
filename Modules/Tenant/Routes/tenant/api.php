<?php


use Illuminate\Support\Facades\Route;
use Modules\Tenant\Http\Controllers\AdminWorkScheduleController;
use Modules\Tenant\Http\Controllers\TenantController;
use Modules\Tenant\Http\Controllers\WorkScheduleController;


Route::prefix('admin')
    ->middleware('auth:tenant_admin')
    ->group(
        function () {
            Route::apiResource('workSchedule', AdminWorkScheduleController::class);

            Route::put('tenant', [TenantController::class, 'update']);
        }
    );

Route::get('/tenant', [TenantController::class, 'show']);

Route::prefix('work-schedule')->group(
    function () {
        Route::get('list', [WorkScheduleController::class, 'index']);
        Route::get('is-open', [WorkScheduleController::class, 'isOpen']);
    }
);
