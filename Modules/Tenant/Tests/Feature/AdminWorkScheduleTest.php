<?php

uses(Tests\TestCase::class);


beforeEach(function () {
    initializeTenancy();
});

test('admin cat see the list of work schedules', function () {


    $this->actingAs(tenantAdmin(), 'tenant_admin');

    \Modules\Tenant\Entities\WorkSchedule::factory()->count(5)->create();

    $res = $this->getJson('/api/admin/workSchedule');

    $res->assertStatus(200);

    $res->assertJsonCount(5, 'data');

    $res->assertJson(
        fn(\Illuminate\Testing\Fluent\AssertableJson $json) => $json->has('data.0.id')
            ->has('data.0.work_day')
            ->has('data.0.start_time')
            ->has('data.0.end_time')
    );
});


test(
    'admin can create a work schedule',
    function () {
        $this->actingAs(tenantAdmin(), 'tenant_admin');

        $workSchedule = \Modules\Tenant\Entities\WorkSchedule::factory()->makeOne();

        $res = $this->postJson('/api/admin/workSchedule', $workSchedule->toArray());

        $res->assertStatus(201);

        $res->assertJson(
            fn(\Illuminate\Testing\Fluent\AssertableJson $json) => $json->has('data.id')
                ->has('data.work_day')
                ->has('data.start_time')
                ->has('data.end_time')
        );
    }
);

test('work day is required', function () {
    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $workSchedule = \Modules\Tenant\Entities\WorkSchedule::factory()->makeOne(['work_day' => null]);

    $res = $this->postJson('/api/admin/workSchedule', $workSchedule->toArray());

    $res->assertStatus(422);

    $res->assertJsonValidationErrors('work_day');
});

test('start time is required', function () {


    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $workSchedule = \Modules\Tenant\Entities\WorkSchedule::factory()->makeOne(['start_time' => null]);

    $res = $this->postJson('/api/admin/workSchedule', $workSchedule->toArray());

    $res->assertStatus(422);

    $res->assertJsonValidationErrors('start_time');
});

test('end time is required', function () {


    $this->actingAs(tenantAdmin(), 'tenant_admin');

    $workSchedule = \Modules\Tenant\Entities\WorkSchedule::factory()->makeOne(['end_time' => null]);

    $res = $this->postJson('/api/admin/workSchedule', $workSchedule->toArray());

    $res->assertStatus(422);

    $res->assertJsonValidationErrors('end_time');
});


test(
    'admin can update a work schedule',
    function () {


        $this->actingAs(tenantAdmin(), 'tenant_admin');

        $workSchedule = \Modules\Tenant\Entities\WorkSchedule::factory()->createOne();

        $res = $this->putJson("/api/admin/workSchedule/{$workSchedule->id}", ['work_day' => 1, 'start_time' => '08:00', 'end_time' => '18:00']);

        $res->assertStatus(200);

        $res->assertJson(
            fn(\Illuminate\Testing\Fluent\AssertableJson $json) => $json->has('data.id')
                ->has('data.work_day')
                ->has('data.start_time')
                ->has('data.end_time')
        );

        $this->assertDatabaseHas('work_schedules', ['id' => $workSchedule->id, 'work_day' => 1, 'start_time' => '08:00', 'end_time' => '18:00']);
    }
);

test(
    'admin can delete a work schedule',
    function () {


        $this->actingAs(tenantAdmin(), 'tenant_admin');

        $workSchedule = \Modules\Tenant\Entities\WorkSchedule::factory()->createOne();

        $res = $this->deleteJson("/api/admin/workSchedule/{$workSchedule->id}");

        $res->assertStatus(200);

        $res->assertJson(
            fn(\Illuminate\Testing\Fluent\AssertableJson $json) => $json->has('message')
        );

        $this->assertDatabaseMissing('work_schedules', ['id' => $workSchedule->id]);
    }
);
