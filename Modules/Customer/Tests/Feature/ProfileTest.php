<?php

uses(Tests\TestCase::class);


test(
    'customer can edit his name',
    function () {
        $customer = customer();
        $this->actingAs($customer);

        $this->putJson("/api/customer/edit", [
            'name' => 'mahdi',
        ])->assertOk();


        $this->assertDatabaseHas('customers', [
            'name' => 'mahdi',
        ]);
    }
);

test(
    'the name field is required',
    function () {
        $customer = customer();
        $this->actingAs($customer);

        $this->putJson("/api/customer/edit", [
            'name' => '',
        ])->assertJsonValidationErrorFor('name');
    }
);


test(
    'customer can see his profile',
    function () {
        $customer = customer();
        $this->actingAs($customer);

        $this->getJson("/api/customer/profile")
            ->assertOk()
            ->assertJson([
                'data' => [
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                ]
            ]);
    }
);
