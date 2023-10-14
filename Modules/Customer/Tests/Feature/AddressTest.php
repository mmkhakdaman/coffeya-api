<?php

uses(Tests\TestCase::class);


test(
    'customer can make an address',
    function () {
        $customer = customer();
        $this->actingAs($customer);

        $this->post("/api/customer/address", [
            'name' => 'Address Name',
            'address' => 'Address Address',
        ])->assertCreated();

        $this->assertDatabaseHas('addresses', [
            'name' => 'Address Name',
            'address' => 'Address Address',
            'customer_id' => $customer->id,
        ]);
    }
);

test(
    'customer can see the list of addresses',
    function () {
        $customer = customer();
        $this->actingAs($customer);

        $this->get("/api/customer/address")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'address',
                    ],
                ],
            ]);
    }
);

test(
    'customer can update an address',
    function () {
        $customer = customer();
        $this->actingAs($customer);

        $address = \Modules\Customer\Entities\Address::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $this->put("/api/customer/address/{$address->id}", [
            'name' => 'Address Name',
            'address' => 'Address Address',
        ])->assertOk();

        $this->assertDatabaseHas('addresses', [
            'name' => 'Address Name',
            'address' => 'Address Address',
            'customer_id' => $customer->id,
        ]);
    }
);


test(
    'customer can delete an address',
    function () {
        $customer = customer();
        $this->actingAs($customer);

        $address = \Modules\Customer\Entities\Address::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $this->delete("/api/customer/address/{$address->id}")
            ->assertOk();

        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }
);
