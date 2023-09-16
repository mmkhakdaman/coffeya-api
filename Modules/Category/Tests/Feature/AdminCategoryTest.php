<?php

use App\Models\User;

uses(Tests\TestCase::class);


test('it can see the list of categories', function () {
    createUserWithLogin();

    $this->get('/api/admin/category/list')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

test('it can create a category', function () {
    createUserWithLogin();
    $this->post('/api/admin/category/create', [
        'title' => 'Test Category',
    ])->assertStatus(201);

    $this->assertDatabaseHas('categories', [
        'title' => 'Test Category',
    ]);
});

test('it can update a category', function () {
    createUserWithLogin();
    $category = \Modules\Category\Entities\Category::factory()->create();

    $this->put('/api/admin/category/update/' . $category->id, [
        'title' => 'Test Category Updated',
    ])->assertStatus(200);

    $this->assertDatabaseHas('categories', [
        'title' => 'Test Category Updated',
    ]);
});


test('it can reorder categories', function () {
    createUserWithLogin();
    $category1 = \Modules\Category\Entities\Category::factory()->create();
    $category2 = \Modules\Category\Entities\Category::factory()->create();
    $category3 = \Modules\Category\Entities\Category::factory()->create();

    $this->put('/api/admin/category/reorder', [
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
