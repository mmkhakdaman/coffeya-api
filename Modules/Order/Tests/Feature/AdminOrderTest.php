<?php


uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

test('admin can see list of pending orders', function () {
    $admin = tenantAdmin();
    $order = \Modules\Order\Entities\Order::factory()->create([
        'status' => \Modules\Order\Enums\OrderStatusEnum::PENDING,
    ]);

    $this->actingAs($admin, 'admin')
        ->getJson(route('api.admin.orders.index'))
        ->assertOk()
        ->assertJsonFragment(['id' => $order->id]);
});
