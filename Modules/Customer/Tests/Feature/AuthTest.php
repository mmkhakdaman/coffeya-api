<?php

use Modules\Customer\Entities\OTP;

uses(Tests\TestCase::class);

// test auth

it('should send otp', function () {
    $response = $this->post('/api/customer/auth/send-otp', [
        'phone' => '09123456789',
    ]);

    $response->assertStatus(200);
});

it('should verify otp', function () {
    $this->post('/api/customer/auth/send-otp', [
        'phone' => '09123456789',
    ]);

    $token = OTP::where('mobile', '09123456789')->first()->token;
    
    $response = $this->post('/api/customer/auth/verify', [
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
    $this->post('/api/customer/auth/send-otp', [
        'phone' => '09123456789',
    ]);

    $response = $this->post('/api/customer/auth/verify', [
        'phone' => '09123456789',
        'otp' => '123',
    ]);

    $response->assertStatus(403);
});

test('should not verify otp with invalid phone', function () {
    $this->post('/api/customer/auth/send-otp', [
        'phone' => '09123456789',
    ]);

    $token = OTP::where('mobile', '09123456789')->first()->token;

    $response = $this->post('/api/customer/auth/verify', [
        'phone' => '09123456788',
        'otp' => $token,
    ]);

    $response->assertStatus(403);
});


