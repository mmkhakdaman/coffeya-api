<?php


uses(Tests\TestCase::class);

test('user can make a tenant', function () {
    $user = user();
    $this->actingAs($user);

    $this->post(route('tenant.store'), [
        'name' => 'Tenant Name',
        'domain' => 'tenant-name',
    ])->assertCreated();

    $this->assertDatabaseHas('tenants', [
        'name' => 'Tenant Name',
        'domain' => 'tenant-name',
        'user_id' => $user->id,
    ]);
});

test('user can see the list of tenants', function () {
    $user = user();
    $this->actingAs($user);

    $this->get(route('tenant.index'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'domain',
                ],
            ],
        ]);
});




