<?php

namespace Database\Factories;

use App\Models\Ticket;
use Carbon\Carbon;
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
        $bought = $this->faker->dateTimeBetween('-4 month', '-3 month');
        $expiration = Carbon::create($bought)->add(30, 'days');

        return [
            'bought' => $bought->format('Y-m-d'),
            'expiration' => $expiration->format('Y-m-d'),
            'code' => $this->faker->unique()->bothify('?#?#?#'),
        ];
    }
}
