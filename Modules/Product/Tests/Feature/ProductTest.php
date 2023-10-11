<?php


uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

test('customer can see active product list', function () {
    \Modules\Product\Entities\Product::factory()->count(3)->create([
        'is_active' => true
    ]);

    $product_counts = \Modules\Product\Entities\Product::query()->where('is_active', true)->count();

    $this->get('/api/product/list')->assertOk()->assertJsonCount($product_counts, 'data');
});


