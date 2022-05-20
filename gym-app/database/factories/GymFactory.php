<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gym>
 */
class GymFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word . " Edzőterem",
            'address' => $this->faker->word . " utca " . $this->faker->numberBetween(1, 20) . ".",
            'description' => $this->faker->sentence(),
        ];
    }
}
