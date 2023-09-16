<?php


uses(Tests\TestCase::class);

test('customer can see active product list', function () {
    \Modules\Product\Entities\Product::factory()->count(3)->create([
        'is_active' => true
    ]);
    $this->get('/api/product/list')->assertOk()->assertJsonCount(3, 'data');
});


