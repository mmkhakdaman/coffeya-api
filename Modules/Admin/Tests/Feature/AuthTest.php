<?php

use Modules\OTP\Entities\OTP;

uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

// test auth

test('admin can login', function () {
    $admin = \Modules\Admin\Entities\Admin::factory()->create();


    $this->postJson('/api/admin/auth/login', [
        'phone' => $admin->phone,
        'password' => 'password'
    ])->assertOk()->assertJsonStructure([
        'access_token',
        'refresh_token',
        'token_type',
        'expires_in'
    ]);

    $this->assertAuthenticatedAs($admin, 'tenant_admin');
});

test('admin can refresh token', function () {
    $admin = \Modules\Admin\Entities\Admin::factory()->create();

    $this->postJson('/api/admin/auth/login', [
        'phone' => $admin->phone,
        'password' => 'password'
    ])->assertOk();

    $this->postJson('/api/admin/auth/refresh')->assertOk()->assertJsonStructure([
        'access_token',
        'token_type',
        'expires_in'
    ]);
});


test('admin can logout', function () {
    $admin = \Modules\Admin\Entities\Admin::factory()->create();

    $this->postJson('/api/admin/auth/login', [
        'phone' => $admin->phone,
        'password' => 'password'
    ])->assertOk();

    $this->postJson('/api/admin/auth/logout')->assertOk()->assertJsonStructure([
        'message'
    ]);
});



