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
    }
}
