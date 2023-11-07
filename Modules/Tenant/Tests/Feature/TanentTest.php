<?php


uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});


test('every one can see the tenant data', function () {
    \Illuminate\Support\Facades\Storage::fake('public');
    $image = \Illuminate\Http\UploadedFile::fake()->image('logo.jpg')->store('tenant/logo');
    tenant()->update([
        'phone' => '09944432552',
        'address' => 'Jl. Entropy',
        'logo' => $image,
        'location' => 'Jakarta',
    ]);

    $this->getJson('/api/tenant')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'logo',
                'phone',
                'address',
                'location',
                'domain'
            ],
        ]);
});


test('admin can update the tenant data', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');
    \Illuminate\Support\Facades\Storage::fake('public');

    $res = $this->putJson('/api/admin/tenant', [
        'phone' => '09944432552',
        'address' => 'Jl. Entropy',
        'logo' => \Illuminate\Http\UploadedFile::fake()->image('logo.jpg'),
        'location' => 'Jakarta',
    ]);


    $res
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'logo',
                'phone',
                'address',
                'location',
                'domain'
            ],
        ]);
});
