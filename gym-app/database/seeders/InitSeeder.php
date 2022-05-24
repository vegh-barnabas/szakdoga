<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Remove previous data
        DB::table('enterances')->truncate();
        DB::table('tickets')->truncate();
        DB::table('buyable_tickets')->truncate();
        DB::table('category_gym')->truncate();
        DB::table('gyms')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        // Admin
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@br.hu',
            'password' => Hash::make('password'),
            'permission' => 'admin',
        ]);
    }
}
