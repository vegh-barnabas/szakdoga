<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bought' => $this->faker->dateTimeBetween('-2 month', '+1 month'),
            'expiration' => $this->faker->dateTimeBetween('-2 month', '+1 month'),
        ];
    }
}
