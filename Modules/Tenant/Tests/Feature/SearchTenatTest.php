<?php


uses(Tests\TestCase::class);


test('every one can search the tenants name', function () {
    \Modules\Tenant\Entities\Tenant::factory()
        ->for(user())
        ->create([
            'name' => 'entropy',
        ]);


    $this->getJson('/api/tenant/search?name=entropy')
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
