<?php

uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

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
                        ],
                    ],
                ],
            ],
        ]);
});
