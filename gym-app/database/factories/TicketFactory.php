<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Ticket;

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
        $code = "";
        do {
            $code = $this->faker->bothify('?#?#?#');
        } while(Ticket::all()->where('code', $code)->count() > 0);

        return [
            'bought' => $this->faker->dateTimeBetween('-2 month', '+1 month'),
            'expiration' => $this->faker->dateTimeBetween('-2 month', '+1 month'),
            'code' => $code
        ];
    }
}
