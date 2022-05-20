<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BuyableTickets>
 */
class BuyableTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => $this->faker->boolean() ? 'monthly' : 'one-time',
            'name' => $this->faker->unique()->word(rand(1, 3), true),
            'description' => $this->faker->sentence(),
            'quantity' => $this->faker->boolean() ? 999 : $this->faker->numberBetween(0, 30),
            'price' => $this->faker->boolean() ? 0 : $this->faker->numberBetween(500, 10000),
            'hidden' => $this->faker->boolean(),
        ];
    }
}
