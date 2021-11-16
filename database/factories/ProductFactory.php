<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->numberBetween(30, 1200),
            'rating' => $this->faker->numberBetween(1, 5),
            'avail' => $this->faker->numberBetween(0, 1),
            'category_id' => $this->faker->numberBetween(7, 26),
            'brand_id' => $this->faker->numberBetween(1, 8)
        ];
    }
}
