<?php

uses(Tests\TestCase::class);


beforeEach(function () {
    initializeTenancy();
});

// crud testing api

test('admin can see all discounts', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $response = $this->get('api/payment/discounts');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'admin_id',
                'code',
                'usage_limitation',
                'user_limitation',
                'percent',
                'price',
                'expire_at',
                'status',
            ],
        ],
    ]);
});


test('admin can store discount', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->make();

    $response = $this->post('api/payment/discounts', $discount);

    $response->assertStatus(201);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'admin_id',
            'code',
            'usage_limitation',
            'user_limitation',
            'percent',
            'price',
            'expire_at',
            'status',
        ],
    ]);
});

// validation testing api

test('the code field is required', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->make(['code' => '']);

    $response = $this->post('api/payment/discounts', $discount->toArray());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('code');
});

test('the code field must be unique', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->create();

    $response = $this->post('api/payment/discounts', [
        'code' => $discount->code,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('code');
});

test('the percent or price field is required', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->make(['percent' => '', 'price' => '']);

    $response = $this->post('api/payment/discounts', $discount->toArray());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['percent', 'price']);
});

test('the percent most be between 0 and 100', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->make(['percent' => 101]);

    $response = $this->post('api/payment/discounts', $discount->toArray());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('percent');
});

test('the percent field must be numeric', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->make(['percent' => 'string']);

    $response = $this->post('api/payment/discounts', $discount->toArray());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('percent');
});

test('the price field must be numeric', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->make(['price' => 'string']);

    $response = $this->post('api/payment/discounts', $discount->toArray());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('price');
});

test('the expire_at most be greater than now', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->make(['expire_at' => now()->subDay()]);

    $response = $this->post('api/payment/discounts', $discount->toArray());

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('expire_at');
});



test('admin can see discount', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->create();

    $response = $this->get("api/payment/discounts/{$discount->id}");

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'admin_id',
            'code',
            'usage_limitation',
            'user_limitation',
            'percent',
            'price',
            'expire_at',
            'status',
        ],
    ]);
});

test('admin can update discount', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->create();

    $response = $this->put("api/payment/discounts/{$discount->id}", [
        'code' => 'new code',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'admin_id',
            'code',
            'usage_limitation',
            'user_limitation',
            'percent',
            'price',
            'expire_at',
            'status',
        ],
    ]);
});

test('admin can delete discount', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $discount = \Modules\Payment\Entities\Discount::factory()->create();

    $response = $this->delete("api/payment/discounts/{$discount->id}");

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'id',
            'admin_id',
            'code',
            'usage_limitation',
            'user_limitation',
            'percent',
            'price',
            'expire_at',
            'status',
        ],
    ]);
});



