<?php


uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

test('admin can see list of all order with pagination', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    \Modules\Order\Entities\Order::factory()->count(10)->create();


    $res = $this->getJson('/api/admin/orders')
        ->assertOk()
        ->assertJsonCount(\Modules\Order\Entities\Order::count(), 'data');
    assertOrderJsonStructure($res);
});
test('admin can see list of pending order with pagination', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    \Modules\Order\Entities\Order::factory()->count(10)->create(
        [
            'status' => 'pending',
        ]
    );

    $res = $this->getJson('/api/admin/orders?status=pending')
        ->assertOk()
        ->assertJsonCount(\Modules\Order\Entities\Order::where('status', 'pending')->count(), 'data');

    assertOrderJsonStructure($res);
});

test('admin can see list of processing order with pagination', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    \Modules\Order\Entities\Order::factory()->count(10)->create(
        [
            'status' => 'confirmed',
        ]
    );


    $res = $this->getJson('/api/admin/orders?status=confirmed')
        ->assertOk()
        ->assertJsonCount(\Modules\Order\Entities\Order::where('status', 'confirmed')->count(), 'data');
    assertOrderJsonStructure($res);
});

test('admin can see list of completed order with pagination', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    \Modules\Order\Entities\Order::factory()->count(10)->create(
        [
            'status' => 'completed',
        ]
    );

    $res = $this->getJson('/api/admin/orders?status=completed')
        ->assertOk()
        ->assertJsonCount(\Modules\Order\Entities\Order::where('status', 'completed')->count(), 'data');

    assertOrderJsonStructure($res);
});

test('admin users can update the order status', function () {
    $this->withoutExceptionHandling();
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $order = \Modules\Order\Entities\Order::factory()->create(
        [
            'status' => 'pending',
        ]
    );

    $this->putJson("/api/admin/orders/{$order->id}", ['status' => 'confirmed'])
        ->assertOk();

    $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'confirmed']);
});


function assertOrderJsonStructure($response)
{
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'customer',
                'table',
                'is_delivery',
                'address',
                'is_packaging',
                'description',
                'status',
                'pending_at',
                'post_cost',
                'order_price',
                'total_price',
            ],
        ],
    ]);
}
