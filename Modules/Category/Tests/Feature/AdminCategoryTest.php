<?php


uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

test('it can see the list of categories', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin')->get('/api/admin/category/list')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                ],
            ],
        ]);
});

test('it can see the list of categories with products', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin')->get('/api/admin/category/list?with_product=true')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'products' => [
                        '*' => [
                            'id',
                            'title',
                        ],
                    ],
                ],
            ],
        ]);
});

test('it can create a category', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin')->post('/api/admin/category/create', [
        'title' => 'Test Category',
    ])->assertStatus(201);

    $this->assertDatabaseHas('categories', [
        'title' => 'Test Category',
    ]);
});

test('user can not create a category with repeated title', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->postJson('/api/admin/category/create', [
        'title' => $category->title,
    ])->assertStatus(422)->assertJsonValidationErrors(['title']);
});

test('user can not create a category without title', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $this->postJson('/api/admin/category/create', [
        'title' => '',
    ])->assertStatus(422)->assertJsonValidationErrors(['title']);
});

test('user can not create a category with title more than 255 characters', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $this->postJson('/api/admin/category/create', [
        'title' => str_repeat('a', 256),
    ])->assertStatus(422)->assertJsonValidationErrors(['title']);
});

test('user can not update a category with repeated title', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category1 = \Modules\Category\Entities\Category::factory()->create();
    $category2 = \Modules\Category\Entities\Category::factory()->create();

    $this->putJson('/api/admin/category/update/' . $category1->id, [
        'title' => $category2->title,
    ])->assertStatus(422)->assertJsonValidationErrors(['title']);
});

test('user can not update a category without title', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->putJson('/api/admin/category/update/' . $category->id, [
        'title' => '',
    ])->assertStatus(422)->assertJsonValidationErrors(['title']);
});

test('user can not update a category with title more than 255 characters', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->putJson('/api/admin/category/update/' . $category->id, [
        'title' => str_repeat('a', 256),
    ])->assertStatus(422)->assertJsonValidationErrors(['title']);
});



test('it can update a category', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->putJson('/api/admin/category/update/' . $category->id, [
        'title' => 'Test Category Updated',
    ])->assertStatus(200);

    $this->assertDatabaseHas('categories', [
        'title' => 'Test Category Updated',
    ]);
});


test('it can reorder categories', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category1 = \Modules\Category\Entities\Category::factory()->create();
    $category2 = \Modules\Category\Entities\Category::factory()->create();
    $category3 = \Modules\Category\Entities\Category::factory()->create();

    $this->putJson('/api/admin/category/reorder', [
        'categories' => [
            [
                'id' => $category1->id,
                'order' => 3,
            ],
            [
                'id' => $category2->id,
                'order' => 1,
            ],
            [
                'id' => $category3->id,
                'order' => 2,
            ],
        ],
    ])->assertStatus(200);

    $this->assertDatabaseHas('categories', [
        'id' => $category1->id,
        'order' => 3,
    ]);

    $this->assertDatabaseHas('categories', [
        'id' => $category2->id,
        'order' => 1,
    ]);

    $this->assertDatabaseHas('categories', [
        'id' => $category3->id,
        'order' => 2,
    ]);
});


test('admin can disable a category', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->putJson('/api/admin/category/disable/' . $category->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'is_active' => false,
    ]);
});

test('admin can enable a category', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    $category = \Modules\Category\Entities\Category::factory()->create([
        'is_active' => false,
    ]);

    $this->putJson('/api/admin/category/enable/' . $category->id)
        ->assertStatus(200);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'is_active' => true,
    ]);
});

