 <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\AddressController;
use Modules\Customer\Http\Controllers\AuthController;
use Modules\Customer\Http\Controllers\CustomerController;

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

Route::prefix('customer')
    ->group(function () {
        Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/auth/verify', [AuthController::class, 'verifyOtp']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    });


Route::middleware('auth:customer')
    ->prefix('customer')
    ->group(function () {
        Route::apiResource('address', AddressController::class);
        Route::put('/edit', [CustomerController::class, 'update']);
        Route::get('/profile', [CustomerController::class, 'profile']);
    });
