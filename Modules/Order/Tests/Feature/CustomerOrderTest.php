<?php

use Modules\Product\Entities\Product;
use Modules\Table\Entities\Table;

uses(Tests\TestCase::class);


// testing ordering products by price in customer side

it('can add product to cart', function () {
    $products = Product::factory()->count(10)->create();
    $table = Table::factory()->create([
        'token' => 'test_token',
    ]);

    $this->post('api/order/add-to-cart', [
        'cart_id' => [
            [
                'product_id' => $products->first()->id,
                'quantity' => 1,
            ],
            [
                'product_id' => $products->last()->id,
                'quantity' => 1,
            ]
        ],
        'description' => 'test description',
        'table_token' => $table->token,
    ])->assertStatus(200);
});

it('can add product to cart with wrong table token', function () {
    $products = Product::factory()->count(10)->create();
    $table = Table::factory()->create([
        'token' => 'test_token',
    ]);

    $this->post('api/order/add-to-cart', [
        'cart_id' => [
            [
                'product_id' => $products->first()->id,
                'quantity' => 1,
            ],
            [
                'product_id' => $products->last()->id,
                'quantity' => 1,
            ]
        ],
        'description' => 'test description',
        'table_token' => 'wrong_token',
    ])->assertStatus(404);
});

it('can add product to cart with wrong product id', function () {
    $products = Product::factory()->count(10)->create();
    $table = Table::factory()->create([
        'token' => 'test_token',
    ]);

    $this->post('api/order/add-to-cart', [
        'cart_id' => [
            [
                'product_id' => 100,
                'quantity' => 1,
            ],
            [
                'product_id' => $products->last()->id,
                'quantity' => 1,
            ]
        ],
        'description' => 'test description',
        'table_token' => 'test_token',
    ])->assertStatus(404);
});

it('cart most have at least one product', function () {
    $table = Table::factory()->create([
        'token' => 'test_token',
    ]);

    $this->post('api/order/add-to-cart', [
        'cart_id' => [],
        'description' => 'test description',
        'table_token' => 'test_token',
    ])->assertStatus(422);
});

it('cart most ordered when customer pay', function () {
    $table = Table::factory()->create([
        'token' => 'test_token',
    ]);

    $this->post('api/order/pay', [
        'table_token' => 'test_token',
    ])->assertStatus(422);
});
