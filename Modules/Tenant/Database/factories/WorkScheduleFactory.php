<?php

namespace Modules\Tenant\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Tenant\Entities\WorkSchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $end_time = $this->faker->time('H:i');
        $start_time = $this->faker->time('H:i', $max = $end_time);


        return [
            'work_day' => $this->faker->numberBetween(1, 7),
            'start_time' => $start_time,
            'end_time' => $end_time,
        ];
    }
}

