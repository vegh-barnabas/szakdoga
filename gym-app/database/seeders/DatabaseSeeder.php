<?php

namespace Database\Seeders;

use App\Models\BuyableTicket;
use App\Models\Category;
use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use Carbon\CarbonImmutable;
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
        // Remove previous data
        DB::table('enterances')->truncate();
        DB::table('tickets')->truncate();
        DB::table('buyable_tickets')->truncate();
        DB::table('category_gym')->truncate();
        DB::table('gyms')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        /* Gyms */
        Gym::factory()->create([
            'name' => "Sajt utcai edzőterem",
            'address' => "Sajt utca 11",
            'description' => "Közel a Kálvin térhez, a Sajt utcában helyezkedik el a jól felszerelt edzőtermünk. Mindenkit szívesen várunk. Kedvezményes árak, kedves recepciósok!",
        ]);
        Gym::factory()->create([
            'name' => "Harap utcai edzőterem",
            'address' => "Harap utca 3",
            'description' => "Astoria körül éjjel nappali edzőterem, a szauna elromlott :(",
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
            'permission' => 'admin',
        ]);

        // Receptionists
        for ($i = 1; $i <= 2; $i++) {
            User::factory()->create([
                'name' => 'receptionist' . $i,
                'email' => 'receptionist' . $i . '@br.hu',
                'password' => Hash::make('password'),
                'permission' => 'receptionist',
                'prefered_gym' => $gyms->random()->id,
            ]);
        }

        // Users
        for ($i = 1; $i <= 10; $i++) {
            if (rand(0, 1)) {
                User::factory()->create([
                    'name' => 'user' . $i,
                    'email' => 'user' . $i . '@br.hu',
                    'password' => Hash::make('password'),
                    'prefered_gym' => Gym::all()->pluck('id')->random(),
                ]);
            } else {
                User::factory()->create([
                    'name' => 'user' . $i,
                    'email' => 'user' . $i . '@br.hu',
                    'password' => Hash::make('password'),
                ]);
            }
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
            'type' => 'monthly',
            'name' => 'Normál bérlet',
            'description' => 'Sima bérlet, feljogosít minden használatára az edzőteremben',
            'quantity' => 999,
            'price' => 8000,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'monthly',
            'name' => 'Diákbérlet',
            'description' => 'Hónapos normál bérlet diákkedvezménnyel (diákigazolvány szükséges!)',
            'quantity' => 999,
            'price' => 5000,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'one-time',
            'name' => 'Normál jegy',
            'description' => 'Napi normál jegy, feljogosít minden használatára az edzőteremben',
            'quantity' => 999,
            'price' => 2000,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'one-time',
            'name' => 'Diákjegy',
            'description' => 'Napi normál jegy diákkedvezménnyel, feljogosít minden használatára az edzőteremben (diákigazolvány szükséges!)',
            'quantity' => 999,
            'price' => 1500,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 1,
            'type' => 'one-time',
            'name' => 'Szaunajegy',
            'description' => 'Első 5 darab 50% kedvezményes áron! Csak szaunára vonatkozik',
            'quantity' => 5,
            'price' => 500,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 2,
            'type' => 'monthly',
            'name' => 'Normál bérlet',
            'description' => 'Sima bérlet, feljogosít minden használatára az edzőteremben',
            'quantity' => 999,
            'price' => 8000,
            'hidden' => 0,
        ]);
        BuyableTicket::factory()->create([
            'gym_id' => 2,
            'type' => 'one-time',
            'name' => 'Szaunajegy',
            'description' => 'Csak szaunára vonatkozik',
            'quantity' => 999,
            'price' => 1000,
            'hidden' => 0,
        ]);

        /* Tickets */
        $users = User::all()->where('permission', 'user');
        $buyable_tickets = BuyableTicket::all()->filter(function ($ticket) {
            return !$ticket->isMonthly();
        });
        $buyable_monthly_tickets = BuyableTicket::all()->filter(function ($ticket) {
            return $ticket->isMonthly();
        });
        foreach ($users as $user) {
            // one-time tickets
            for ($i = 0; $i < rand(0, 10); $i++) {
                $random_gym_id = $gyms->random()->id;
                $gym_buyable_ticket = $buyable_tickets->where('gym_id', $random_gym_id)->random();
                Ticket::factory()->create([
                    'user_id' => $user,
                    'gym_id' => $random_gym_id,
                    'type_id' => $gym_buyable_ticket->id,
                ]);
            }

            // monthly tickets
            $random_gym_id = $gyms->random()->id;
            $gym_random_buyable_monthly_tickets = $buyable_monthly_tickets
                ->where('gym_id', $random_gym_id)
                ->random(rand(0, $buyable_monthly_tickets->where('gym_id', $random_gym_id)->count()));

            foreach ($gym_random_buyable_monthly_tickets as $gym_random_buyable_monthly_ticket) {
                Ticket::factory()->create([
                    'user_id' => $user,
                    'gym_id' => $random_gym_id,
                    'type_id' => $gym_random_buyable_monthly_ticket->id,
                ]);
            }
        }

        /* Enterances */
        $tickets = Ticket::all();
        $random_tickets = $tickets->random(rand(0, $tickets->count()));

        foreach ($random_tickets as $random_ticket) {
            $enterances = 1;
            if ($random_ticket->isMonthly()) {
                $enterances = rand(0, 15);
            }

            for ($i = 0; $i < $enterances; $i++) {
                $random_ticket_bought_date = CarbonImmutable::Create($random_ticket->bought);

                $random_enterance_date = $random_ticket_bought_date->add(rand(0, 20), 'day')->add(rand(0, 12), 'hour')->add(rand(0, 55), 'minute');

                $random_exit_date = CarbonImmutable::Create($random_enterance_date)->add(rand(1, 4), 'hour')->add(rand(11, 55), 'minute');

                Enterance::factory()->create([
                    'gym_id' => $random_ticket->gym->id,
                    'user_id' => $random_ticket->user->id,
                    'ticket_id' => $random_ticket->id,
                    'enter' => $random_enterance_date,
                    'exit' => $random_exit_date,
                ]);
            }
        }
    }
}
