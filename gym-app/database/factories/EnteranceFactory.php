<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EnteranceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $enter = $this->faker->dateTimeBetween('-1 week', '-1 day');

        return [
            'enter' => $enter,
            'exit' => $enter
        ];
    }
}
