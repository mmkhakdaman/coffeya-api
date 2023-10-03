<?php

namespace Modules\OTP\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OTPFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\OTP\Entities\OTP::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}

