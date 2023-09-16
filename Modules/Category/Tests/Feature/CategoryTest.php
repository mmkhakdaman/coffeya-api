<?php

uses(Tests\TestCase::class);


test('it can see the list of categories', function () {
    $this->get('/api/category/list')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'order',
                    'products' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'price',
                            'is_active',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});
