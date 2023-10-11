<?php

use Modules\OTP\Entities\OTP;

uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

// test auth

it('should send otp', function () {
    $response = $this->post('/api/admin/auth/send-otp', [
        'phone' => '09123456789',
    ]);

    $response->assertStatus(200);
});

it('should verify otp', function () {
    $user = \Modules\Admin\Entities\Admin::factory()->create([
        'phone' => '09123456789'
    ]);

    $this->post('/api/admin/auth/send-otp', [
        'phone' => $user->phone,
    ]);

    $token = $user->otps()->first()->token;

    $response = $this->post('/api/admin/auth/verify', [
        'phone' => '09123456789',
        'otp' => $token,
    ]);


    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'access_token',
            'token_type',
            'expires_in',
        ]
    ]);
});

test('should not verify otp with invalid token', function () {
    $this->post('/api/admin/auth/send-otp', [
        'phone' => '09123456789',
    ]);

    $response = $this->post('/api/admin/auth/verify', [
        'phone' => '09123456789',
        'otp' => '123',
    ]);

    $response->assertStatus(403);
});

test('should not verify otp with invalid phone', function () {
    $user = \Modules\Admin\Entities\Admin::factory()->create([
        'phone' => '09123456789'
    ]);


    $this->post('/api/admin/auth/send-otp', [
        'phone' => $user->phone,
    ]);
    $otp = $user->otps()->first();

    \Pest\Laravel\assertDatabaseHas('otps', [
        "id"=>$otp->id,
        "mobile"=>$otp->mobile,
        "token"=>$otp->token,
        "expires_on"=>$otp->expires_on,
        "otpable_type"=>$otp->otpable_type,
        "otpable_id"=>$otp->otpable_id,
    ]);

    $token = $user->otps()->first()->token;

    $response = $this->post('/api/admin/auth/verify', [
        'phone' => '09123456788',
        'otp' => $token,
    ]);

    $response->assertStatus(403);
});


