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
        // $enter = $this->faker->dateTimeBetween('-1 week', '-1 day');
        // $carbon_enter = Carbon::create($enter);

        // $carbon_exit = $carbon_enter;
        // // Add hour
        // if (rand(0, 1)) {
        //     $rand_hour = rand(1, 4);
        //     $carbon_exit->add($rand_hour, 'hour');
        // }
        // // Add minute
        // $rand_minute = rand(11, 55);
        // $carbon_exit->add($rand_minute, 'minute');

        // return [
        //     'enter' => $carbon_enter,
        //     'exit' => $carbon_exit,
        // ];

        return [];
    }
}
