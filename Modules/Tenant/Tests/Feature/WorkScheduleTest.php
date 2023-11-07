<?php


uses(Tests\TestCase::class);

beforeEach(function () {
    initializeTenancy();
});

test(
    'every user can see the work schedule',
    function () {
        $this->withoutExceptionHandling();

        \Modules\Tenant\Entities\WorkSchedule::factory()->count(5)->create();


        $res = $this->getJson('/api/work-schedule/list');


        $res->assertStatus(200);
        $res->assertJsonCount(5, 'data');
        $res->assertJson(
            fn(\Illuminate\Testing\Fluent\AssertableJson $json) => $json->has('data.0.id')
                ->has('data.0.work_day')
                ->has('data.0.start_time')
                ->has('data.0.end_time')
        );
    }
);


test(
    'shop most be open in the valid time',
    function () {
        $this->withoutExceptionHandling();

        \Modules\Tenant\Entities\WorkSchedule::factory()->create(
            [
                'work_day' => \Carbon\Carbon::now()->dayOfWeek,
                'start_time' => now()->subMinutes(30)->format('H:i:s'), // 30 minutes ago (open)
                'end_time' => now()->addMinutes(30)->format('H:i:s'), // 30 minutes from now (open)
            ]
        );

        $res = $this->getJson('/api/work-schedule/is-open');

        $res->assertStatus(200);

        $res->assertJson(['data' => true]);
    }
);


test(
    'shop most be closed in the invalid time',
    function () {
        $this->withoutExceptionHandling();

        \Modules\Tenant\Entities\WorkSchedule::factory()->create(
            [
                'work_day' => \Carbon\Carbon::now()->dayOfWeek,
                'start_time' => now()->addMinutes(30)->format('H:i:s'), // 30 minutes from now (closed)
                'end_time' => now()->addMinutes(60)->format('H:i:s'), // 60 minutes from now (closed)
            ]
        );

        $res = $this->getJson('/api/work-schedule/is-open');

        $res->assertStatus(200);

        $res->assertJson(['data' => false]);
    }
);
