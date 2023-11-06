<?php


use Illuminate\Support\Facades\Storage;

uses(Tests\TestCase::class);


beforeEach(function () {
    initializeTenancy();
});


test('it can see the list of products', function () {
    \Modules\Product\Entities\Product::factory()->count(3)->create();

    $this->get('/api/admin/product/list')
        ->assertStatus(200)
        ->assertJsonCount(
            \Modules\Product\Entities\Product::query()->count(),
            'data'
        )
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
    $category = \Modules\Category\Entities\Category::factory()->create();


    $this->postJson('/api/admin/product/create', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ])
        ->assertStatus(201);


    $this->assertDatabaseHas('products', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
        'is_active' => true,
        'in_stock' => true,
    ]);
});

test('title is required', function () {
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->postJson('/api/admin/product/create', [
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ])->assertJsonValidationErrorFor('title');


    $this->assertDatabaseMissing('products', [
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ]);

    $this->assertDatabaseCount('products', 0);

    $this->postJson('/api/admin/product/create', [
        'title' => '',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ])
        ->assertJsonValidationErrorFor('title');

    $this->assertDatabaseMissing('products', [
        'title' => '',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ]);

    $this->assertDatabaseCount('products', 0);

    $this->postJson('/api/admin/product/create', [
        'title' => null,
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ])
        ->assertJsonValidationErrorFor('title');

    $this->assertDatabaseMissing('products', [
        'title' => null,
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ]);

    $this->assertDatabaseCount('products', 0);

    $this->postJson('/api/admin/product/create', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ])
        ->assertStatus(201)
        ->assertJsonMissingValidationErrors('title');

    $this->assertDatabaseHas('products', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ]);

    $this->assertDatabaseCount('products', 1);
});

test('title is unique', function () {
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->postJson('/api/admin/product/create', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ])
        ->assertStatus(201)
        ->assertJsonMissingValidationErrors('title');

    $this->assertDatabaseHas('products', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ]);

    $this->assertDatabaseCount('products', 1);

    $this->postJson('/api/admin/product/create', [
        'title' => 'Test Product',
        'description' => 'Test Product Description',
        'category_id' => $category->id,
        'price' => 100,
    ])
        ->assertJsonValidationErrorFor('title');

    $this->assertDatabaseCount('products', 1);
});

test('it can upload an image', function () {
    Storage::fake('public');
    $image = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');
    $image2 = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');
    $product = \Modules\Product\Entities\Product::factory()->create([
        'image' => "products/{$image->hashName()}",
    ]);

    $this->putJson('/api/admin/product/upload-image/' . $product->id, [
        'image' => $image2,
    ])->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'image' => "products/{$image2->hashName()}",
    ]);

    Storage::disk('public')->assertExists("products/{$image2->hashName()}");
    Storage::disk('public')->assertMissing("products/{$image->hashName()}");
});

test('it can delete image', function () {
    Storage::fake('public');
    $image = \Illuminate\Http\UploadedFile::fake()->image('test.jpg');
    $product = \Modules\Product\Entities\Product::factory()->create([
        'image' => "products/{$image->hashName()}",
    ]);

    $this->deleteJson('/api/admin/product/delete-image/' . $product->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'image' => null,
    ]);

    Storage::disk('public')->assertMissing("products/{$image->hashName()}");
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

test('user can see one product', function () {
    $product = \Modules\Product\Entities\Product::factory()->create();


    $this->get('/api/admin/product/' . $product->id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'category_id',
                'order',
                'price',
                'image',
                'is_active',
                'in_stock',
            ],
        ]);
});


test('user can not update a product with repeat title', function () {
    $product1 = \Modules\Product\Entities\Product::factory()->create();
    $product2 = \Modules\Product\Entities\Product::factory()->create();

    $req = $this->putJson('/api/admin/product/update/' . $product1->id, [
        'title' => $product2->title,
        'description' => 'Test Product Description Updated',
        'category_id' => 1,
        'price' => 100,
    ]);

    $req->assertJsonValidationErrorFor('title');
});

test('user can update the product with the same title', function () {
    $product = \Modules\Product\Entities\Product::factory()->create();

    $req = $this->putJson('/api/admin/product/update/' . $product->id, [
        'title' => $product->title,
        'description' => 'Test Product Description Updated',
        'category_id' => 1,
        'price' => 100,
    ]);

    $req->assertJsonMissingValidationErrors('title');
});
