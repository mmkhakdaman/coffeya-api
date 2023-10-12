<?php

use Modules\Product\Entities\Product;
use Modules\Table\Entities\Table;

uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});


// testing ordering products by price in customer side

// /api/order/check-out
//'cart' => 'required|array',
//'cart.*.product_id' => 'required|integer|exists:products,id',
//'cart.*.quantity' => 'required|integer|min:1',
//'description' => 'nullable|string',


test('customer can order products', function () {
    $customer = customer();

    $table = Table::factory()->create();

    $products = Product::factory()->count(3)->create();

    $cart = $products->map(
        function ($product) {
            return [
                'product_id' => $product->id,
                'quantity' => 1,
            ];
        }
    );

    $response = $this->actingAs($customer, 'customer')
        ->postJson(
            "/api/order/check-out",
            [
                'cart' => $cart,
                'description' => 'test description',
                'table_id' => $table->id,
            ]
        );

    $response->assertJson(fn(\Illuminate\Testing\Fluent\AssertableJson $json) => $json->hasAll(['redirect_url','order_id']));

    $this->assertDatabaseHas(
        'orders',
        [
            'customer_id' => $customer->id,
            'description' => 'test description',
            'table_id' => $table->id,
        ]
    );
    $this->assertDatabaseHas(
        'order_items',
        [
            'order_id' => $response->json('order_id'),
            'product_id' => $products->first()->id,
            'quantity' => 1,
        ],
    );
});
