<?php

namespace Modules\Order\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Entities\Address;
use Modules\Customer\Entities\Customer;
use Modules\Table\Entities\Table;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isDelivery = $this->faker->boolean;
        return [
            'customer_id' => Customer::factory()->create()->id,
            'is_delivery' => $isDelivery,
            'table_id' => Table::factory()->create()->id,
            'address_id' => $isDelivery ? Address::factory()->create()->id : null,
            'is_packaging' => $this->faker->boolean,
            'description' => $this->faker->text,
            'status' => "pending",
            'pending_at' => $this->faker->dateTime(),
            'post_cost' => $isDelivery ? 10000 : 0,
            'is_pay_in_restaurant' => $this->faker->boolean,
            'order_price' => $this->faker->randomFloat(2, 0, 999999.99),
            'total_price' => $this->faker->randomFloat(2, 0, 999999.99),
        ];
    }
}

