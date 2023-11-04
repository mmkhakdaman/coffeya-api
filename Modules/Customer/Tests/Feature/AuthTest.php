<?php

use Modules\OTP\Entities\OTP;

uses(Tests\TestCase::class);

// test auth

it('should send otp', function () {
    $response = $this->postJson('/api/customer/auth/send-otp', [
        'phone' => '09123456789',
    ]);

    $response->assertStatus(200);


    $this->assertDatabaseHas('customers', [
        'phone' => '09123456789',
    ]);

    $this->assertDatabaseHas('otps', [
        'mobile' => '09123456789',
    ]);
});

it('should verify otp', function () {
    $user = \Modules\Customer\Entities\Customer::factory()->create([
        'phone' => '09123456789'
    ]);

    $this->post('/api/customer/auth/send-otp', [
        'phone' => $user->phone,
    ]);

    $token = $user->otps()->first()->token;

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
    $user = \Modules\Customer\Entities\Customer::factory()->create([
        'phone' => '09123456789'
    ]);


    $this->post('/api/customer/auth/send-otp', [
        'phone' => $user->phone,
    ]);

    $token = $user->otps()
        ->first()->token;

    $response = $this->post('/api/customer/auth/verify', [
        'phone' => '09123456788',
        'otp' => $token,
    ]);

    $response->assertStatus(403);
});



test('customers can refresh the token', function () {
    $user = \Modules\Customer\Entities\Customer::factory()->create([
        'phone' => '09123456789'
    ]);

    $this->postJson('/api/customer/auth/send-otp', [
        'phone' => $user->phone,
    ]);

    $token = $user->otps()
        ->first()->token;

    $response = $this->postJson('/api/customer/auth/verify', [
        'phone' => '09123456789',
        'otp' => $token,
    ]);

    $response->assertStatus(200);

    $response = $this->withHeader('Authorization', 'Bearer ' . $response->json('data.access_token'))->postJson('/api/customer/auth/refresh');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'access_token',
            'token_type',
            'expires_in',
        ]
    ]);
});
