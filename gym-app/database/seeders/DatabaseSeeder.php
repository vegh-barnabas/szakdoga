<?php

namespace Database\Seeders;

use App\Models\BuyableTicket;
use App\Models\Category;
use App\Models\Gym;
use App\Models\Locker;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tickets')->truncate();
        DB::table('gyms')->truncate();
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('enterances')->truncate();
        DB::table('buyable_tickets')->truncate();

        /* Gyms */
        Gym::factory()->create([
            'name' => "Sajt utcai edzőterem",
            'address' => "Sajt utca 11",
            'description' => "Közel a Kálvin térhez, a Sajt utcában helyezkedik el a jól felszerelt edzőtermünk. Mindenkit szívesen várunk. Kedvezményes árak, kedves recepciósok!",
        ]);
        $gyms = Gym::all();

        /* Users */

        // Test User
        User::factory()->create([
            'name' => 'test',
            'email' => 'test' . '@br.hu',
            'password' => Hash::make('password'),
            'credits' => 30000,
        ]);

        // Admin
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@br.hu',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Receptionists
        for ($i = 1; $i <= 2; $i++) {
            User::factory()->create([
                'name' => 'receptionist' . $i,
                'email' => 'receptionist' . $i . '@br.hu',
                'password' => Hash::make('password'),
                'is_receptionist' => true,
                'prefered_gym' => 1,
            ]);
        }

        // Users
        for ($i = 1; $i <= 10; $i++) {
            User::factory()->create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@br.hu',
                'password' => Hash::make('password'),
            ]);
        }

        /* Categories */
        $categories = ['szauna', '0-24', 'WC', 'súlyok', 'gépek', 'medence'];

        for ($i = 0; $i < 6; $i++) {
            $category = Category::factory()->create([
                'name' => $categories[$i],
            ]);

            $attach = rand(0, 1);

            if ($attach) {
                $gyms = Gym::all();
                $rand_gym = $gyms->where('id', (rand(1, $gyms->count())))->first();

                $rand_gym->categories()->attach($category->id);
            }
        }

        /* Buyable Tickets */
        // TODO: rethink what hidden should be used for
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'bérlet',
            'name' => 'Normál bérlet',
            'description' => 'Sima bérlet, feljogosít minden használatára az edzőteremben',
            'quantity' => 999,
            'price' => 8000,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'bérlet',
            'name' => 'Diákbérlet',
            'description' => 'Hónapos normál bérlet diákkedvezménnyel (diákigazolvány szükséges!)',
            'quantity' => 999,
            'price' => 5000,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'jegy',
            'name' => 'Normál jegy',
            'description' => 'Napi normál jegy, feljogosít minden használatára az edzőteremben',
            'quantity' => 999,
            'price' => 2000,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'jegy',
            'name' => 'Diákjegy',
            'description' => 'Napi normál jegy diákkedvezménnyel, feljogosít minden használatára az edzőteremben (diákigazolvány szükséges!)',
            'quantity' => 999,
            'price' => 1500,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'jegy',
            'name' => 'Szaunajegy',
            'description' => 'Első 5 darab 50% kedvezményes áron! Csak szaunára vonatkozik',
            'quantity' => 5,
            'price' => 500,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'jegy',
            'name' => 'Szaunajegy',
            'description' => 'Csak szaunára vonatkozik',
            'quantity' => 999,
            'price' => 1000,
            'hidden' => 0,
        ]);

        /* Lockers */
        foreach ($gyms as $gym) {
            for ($i = 0; $i < rand(10, 20); $i++) {
                Locker::factory()->create([
                    'gym_id' => $gym->id,
                    'number' => $i,
                ]);
            }
        }

        // Test user
        Ticket::factory()->create([
            'user_id' => 1,
            'gym_id' => 1,
            'type_id' => 1,
        ]);
        Ticket::factory()->create([
            'user_id' => 1,
            'gym_id' => 1,
            'type_id' => 4,
        ]);
    }
}
