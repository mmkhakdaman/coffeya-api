<?php


use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], static function ($router) {
    $router->any('payments/callback', ['uses' => 'PaymentController@callback', 'as' => 'payment.callback']);
    $router->get(
        'payments/redirect/{gateway}/{id}',
        ['uses' => 'PaymentController@redirect', 'as' => 'payment.redirect']
    );

    $router->get('payments/fail', ['uses' => 'PaymentController@fail', 'as' => 'payment.fail']);
});
