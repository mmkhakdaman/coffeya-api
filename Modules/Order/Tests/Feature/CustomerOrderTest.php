<?php

use Modules\Product\Entities\Product;
use Modules\Table\Entities\Table;

uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});


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
                'is_delivery' => false,
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

test('customer can order products with delivery', function () {
    $customer = customer();

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
                'is_delivery' => true,
                'address' => 'test address',
            ]
        );

    $response->assertJson(fn(\Illuminate\Testing\Fluent\AssertableJson $json) => $json->hasAll(['redirect_url','order_id']));

    $this->assertDatabaseHas(
        'orders',
        [
            'customer_id' => $customer->id,
            'description' => 'test description',
            'address' => 'test address',
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


