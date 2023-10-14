<?php

uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});


it('can list tables', function () {
    $this->actingAs(tenantAdmin(),'tenant_admin');
    \Modules\Table\Entities\Table::factory()->count(3)->create();

    $this->get('/api/admin/table')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
});


it('can create a table', function () {
    $this->actingAs(tenantAdmin(),'tenant_admin');
    $this->post(
        '/api/admin/table',
        [
            'title' => 'Test Title',
        ]
    )
        ->assertStatus(201);
    $this->assertDatabaseHas('tables', [
        'title' => 'Test Title',
    ]);
});


it('can update a table', function () {
    $this->actingAs(tenantAdmin(),'tenant_admin');
    $table = \Modules\Table\Entities\Table::factory()->create();

    $this->put('/api/admin/table/' . $table->id, [
        'title' => 'Test Title',
    ])->assertStatus(200);
    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'title' => 'Test Title',
    ]);
});

it('can show a table', function () {
    $this->actingAs(tenantAdmin(),'tenant_admin');
    $table = \Modules\Table\Entities\Table::factory()->create();

    $this->get('/api/admin/table/' . $table->id)
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $table->id,
                'title' => $table->title,
            ],
        ]);
});

it('can toggle active a table', function () {
    $this->actingAs(tenantAdmin(),'tenant_admin');
    $table = \Modules\Table\Entities\Table::factory()->create([
        'active' => 0,
    ]);

    $this->put('/api/admin/table/' . $table->id . '/toggle-active')
        ->assertStatus(200);
    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'active' => 1,
    ]);
});

it('can toggle in-active a table', function () {
    $this->actingAs(tenantAdmin(),'tenant_admin');
    $table = \Modules\Table\Entities\Table::factory()->create([
        'active' => 1,
    ]);

    $this->put('/api/admin/table/' . $table->id . '/toggle-active')
        ->assertStatus(200);

    $this->assertDatabaseHas('tables', [
        'id' => $table->id,
        'active' => 0,
    ]);
});
