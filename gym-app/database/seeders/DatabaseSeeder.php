<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* Users */
        DB::table('users')->truncate();

        // Admin
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@br.hu',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Receptionists
        for ($i = 1; $i <= rand(2, 4); $i++) {
            User::factory()->create([
                'name' => 'receptionist' . $i,
                'email' => 'receptionist'. $i .'@br.hu',
                'password' => Hash::make('password'),
                'is_receptionist' => true,
            ]);
        }

        // Users
        for ($i = 1; $i <= rand(3, 7); $i++) {
            User::factory()->create([
                'name' => 'user'. $i,
                'email' => 'user'. $i . '@br.hu',
                'password' => Hash::make('password'),
            ]);
        }

        /* Categories */
        $styles = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];

        DB::table('categories')->truncate();

        for ($i = 1; $i <= rand(4, 8); $i++) {
            User::factory()->create([
                'name' => $this->faker->word,
                'style' => $this->faker->randomElement($styles),
            ]);
        }

        /* Gyms */
        DB::table('gyms')->truncate();

        // $categories = Category::all();
        // $categories_count = $categories->count();

        for ($i = 1; $i <= rand(1, 4); $i++) {
            $addr = $this->faker->word;

            // $category_ids = $categories->random(rand(1, $categories_count))->pluck('id')->toArray();

            User::factory()->create([
                'name' => $addr . " utcai edzőterem",
                'address' => $addr . " utca " . $this->faker->numberBetween(1, 20),
                'description' => $this->faker->boolean() ? $this->sentence() : ""
            ]);

            // $post->categories()->attach($category_ids);
        }

    }
}
