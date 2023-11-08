<?php

namespace Modules\Payment\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Payment\Entities\Discount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'admin_id' => \Modules\Admin\Entities\Admin::factory()->create()->id,
            'code' => $this->faker->word,
            'usage_limitation' => $this->faker->randomDigitNotNull,
            'user_limitation' => $this->faker->randomDigitNotNull,
            'percent' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomDigitNotNull,
            'expire_at' => $this->faker->dateTimeBetween('now', '+30 years'),
            'status' => $this->faker->randomElement(["active","inactive"]),
        ];
    }
}

