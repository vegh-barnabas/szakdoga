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
            'type' => $this->faker->boolean() ? 'bérlet' : 'jegy',
            'name' => $this->faker->word(rand(1, 3), true),
            'description' => $this->faker->sentence(),
            'quantity' => $this->faker->boolean() ? 999 : $this->faker->numberBetween(rand(0, 30)),
            'price' => $this->faker->boolean() ? 0 : $this->faker->numberBetween(rand(500, 10000)),
            'hidden' => $this->faker->boolean()
        ];
    }
}
