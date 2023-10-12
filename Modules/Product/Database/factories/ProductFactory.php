<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Database\factories\CategoryFactory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Product\Entities\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'=> $this->faker->title,
            'description'=> $this->faker->text,
            'category_id'=> CategoryFactory::new(),
            'order'=> $this->faker->numberBetween(1, 10),
            'price'=> $this->faker->numberBetween(10000, 1000000),
            'image'=> $this->faker->imageUrl(),
            'is_active'=> $this->faker->boolean,
            'in_stock'=> $this->faker->boolean,
        ];
    }
}

