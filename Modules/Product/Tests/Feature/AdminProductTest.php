<?php


uses(Tests\TestCase::class);


beforeEach(function () {
    initializeTenancy();
});


test('it can see the list of products', function () {
    $this->get('/api/admin/product/list')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'category_id',
                    'order',
                    'price',
                    'image',
                    'is_active',
                    'in_stock',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

test('it can create a product', function () {
    $image = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');
    $category = \Modules\Category\Entities\Category::factory()->create();


    $this->post('/api/admin/product/create', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
        'image' => $image,
    ])->assertStatus(201);

    $this->assertDatabaseHas('products', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
        'image' => "products/{$image->hashName()}",
    ]);
});

test('it can update a product', function () {
    $image = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');
    $product = \Modules\Product\Entities\Product::factory()->create();


    $this->putJson('/api/admin/product/update/' . $product->id, [
        'title' => 'Test Product Updated',
        'description' => 'Test Product Description Updated',
        'category_id' => 1,
        'price' => 100,
        'image' => $image,
    ])->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'title' => 'Test Product Updated',
        'description' => 'Test Product Description Updated',
        'category_id' => 1,
        'price' => 100,
        'image' => "products/{$image->hashName()}",
    ]);
});

test('it can reorder products', function () {
    $product1 = \Modules\Product\Entities\Product::factory()->create();
    $product2 = \Modules\Product\Entities\Product::factory()->create();
    $product3 = \Modules\Product\Entities\Product::factory()->create();


    $this->putJson('/api/admin/product/reorder', [
        'products' => [
            [
                'id' => $product1->id,
                'order' => 3,
            ],
            [
                'id' => $product2->id,
                'order' => 1,
            ],
            [
                'id' => $product3->id,
                'order' => 2,
            ],
        ],
    ])->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product1->id,
        'order' => 3,
    ]);

    $this->assertDatabaseHas('products', [
        'id' => $product2->id,
        'order' => 1,
    ]);

    $this->assertDatabaseHas('products', [
        'id' => $product3->id,
        'order' => 2,
    ]);
});

test('it can delete a product', function () {
    $product = \Modules\Product\Entities\Product::factory()->create();


    $this->delete('/api/admin/product/delete/' . $product->id)
        ->assertStatus(200);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
        'deleted_at' => null,
    ]);
});

test('it can make a product inactive', function () {
    $product = \Modules\Product\Entities\Product::factory()->create([
        'is_active' => true,
    ]);


    $this->putJson('/api/admin/product/toggle-active/' . $product->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'is_active' => false,
    ]);
});

test('it can make a product active', function () {
    $product = \Modules\Product\Entities\Product::factory()->create([
        'is_active' => false,
    ]);


    $this->putJson('/api/admin/product/toggle-active/' . $product->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'is_active' => true,
    ]);
});


test('it can make a product out of stock', function () {
    $product = \Modules\Product\Entities\Product::factory()->create([
        'in_stock' => true,
    ]);


    $this->putJson('/api/admin/product/toggle-stock/' . $product->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'in_stock' => false,
    ]);
});

test('it can make a product in stock', function () {
    $product = \Modules\Product\Entities\Product::factory()->create([
        'in_stock' => false,
    ]);


    $this->putJson('/api/admin/product/toggle-stock/' . $product->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'in_stock' => true,
    ]);
});
