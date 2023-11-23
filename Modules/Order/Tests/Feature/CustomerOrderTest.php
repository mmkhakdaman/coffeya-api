<?php

use Illuminate\Testing\Fluent\AssertableJson;
use Modules\Customer\Entities\Address;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatusEnum;
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

    $response->assertJson(fn(AssertableJson $json) => $json->hasAll(['redirect_url', 'order_id']));

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
    $address = Address::factory()->create(['customer_id' => $customer->id]);

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
                'address_id' => $address->id,
            ]
        );

    $response->assertJson(fn(AssertableJson $json) => $json->hasAll(['redirect_url', 'order_id']));

    $this->assertDatabaseHas(
        'orders',
        [
            'customer_id' => $customer->id,
            'description' => 'test description',
            'is_delivery' => true,
            'address_id' => $address->id,
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


test('customers can see the all not completed order', function () {
    $customer = customer();

    \Modules\Order\Entities\Order::factory()
        ->count(3)
        ->create(
            [
                'customer_id' => $customer->id,
                'status' => fake()->randomElement(['pending', 'confirmed', 'delivered']),
            ]
        );

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/orders");


    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) => $json->has('data', 3));
});

test('customer can see list of completed orders', function () {
    $customer = customer();

    \Modules\Order\Entities\Order::factory()
        ->count(3)
        ->create(
            [
                'customer_id' => $customer->id,
                'status' => fake()->randomElement(['completed', OrderStatusEnum::CANCELLED])
            ]
        );

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/orders/completed");


    $response->assertOk();
    $response->assertJson(fn(AssertableJson $json) => $json->has('data', 3));
});

test(
    'customer can see one of his orders',
    function () {
        $customer = customer();
        $this->actingAs($customer, 'customer');
        $order = Order::factory()->create(['customer_id' => auth()->id()]);
        $this->getJson('/api/order/' . $order->id)
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'status',
                    'total_price',
                    'customer',
                    'address',
                    'table',
                    'items',
                ]
            ]);
    }
);


test('customer can pay this order in the restaurant', function () {
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
                'is_delivery' => false,
                'is_pay_in_restaurant' => true,
            ]
        );

    $response->assertOk();


    $response->assertJson(
        fn(AssertableJson $json) => $json->hasAll(['order_id'])
    );

    $this->assertDatabaseHas(
        'orders',
        [
            'customer_id' => $customer->id,
            'is_pay_in_restaurant' => true,
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

test('customer can pack this order', function () {
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
                'is_delivery' => false,
                'is_pay_in_restaurant' => true,
                'is_packaging' => true,
            ]
        );

    $response->assertOk();


    $response->assertJson(
        fn(AssertableJson $json) => $json->hasAll(['order_id'])
    );

    $this->assertDatabaseHas(
        'orders',
        [
            'customer_id' => $customer->id,
            'is_pay_in_restaurant' => true,
            'is_packaging' => true,
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
