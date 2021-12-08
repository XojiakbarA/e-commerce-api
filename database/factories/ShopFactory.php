<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->company(),
            'rating' => $this->faker->numberBetween(1, 5),
            'address' => $this->faker->unique()->address(),
            'phone_number' => $this->faker->unique()->phoneNumber()
        ];
    }
}
