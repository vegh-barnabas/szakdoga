<?php

namespace Database\Seeders;

use App\Models\BuyableTicket;
use App\Models\Category;
use App\Models\Enterance;
use App\Models\Gym;
use App\Models\Locker;
use App\Models\Ticket;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HerokuSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* Gyms */
        Gym::factory(3)->create();
        $gyms = Gym::all();

        /* Lockers */
        for ($i = 1; $i < rand(15, 30); $i++) {
            Locker::factory()->create([
                'gym_id' => 1,
                'number' => $i,
            ]);
        }
        for ($i = 1; $i < rand(15, 30); $i++) {
            Locker::factory()->create([
                'gym_id' => 2,
                'number' => $i,
            ]);
        }
        for ($i = 1; $i < rand(15, 30); $i++) {
            Locker::factory()->create([
                'gym_id' => 3,
                'number' => $i,
            ]);
        }

        // Admin
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@br.hu',
            'password' => Hash::make('password'),
            'permission' => 'admin',
        ]);

        // Receptionists
        for ($i = 1; $i <= 3; $i++) {
            User::factory()->create([
                'name' => 'receptionist' . $i,
                'email' => 'receptionist' . $i . '@br.hu',
                'password' => Hash::make('password'),
                'permission' => 'receptionist',
                'prefered_gym' => $i,
            ]);
        }

        // Users
        for ($i = 1; $i <= 2; $i++) {
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

        for ($i = 0; $i < count($categories); $i++) {
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
        for ($j = 1; $j <= 3; $j++) {
            for ($i = 0; $i < rand(3, 8); $i++) {
                BuyableTicket::factory()->create([
                    'gym_id' => $j,
                ]);

            }
        }

        /* Tickets */
        $users = User::all()->where('permission', 'user');
        $buyable_tickets = BuyableTicket::all()->filter(function ($ticket) {
            return !$ticket->is_monthly();
        });
        $buyable_monthly_tickets = BuyableTicket::all()->filter(function ($ticket) {
            return $ticket->is_monthly();
        });
        foreach ($users as $user) {
            // one-time tickets
            for ($i = 0; $i < rand(0, 10); $i++) {
                $random_gym_id = $gyms->random()->id;
                $gym_buyable_ticket = $buyable_tickets->where('gym_id', $random_gym_id)->random();
                Ticket::factory()->create([
                    'user_id' => $user,
                    'gym_id' => $random_gym_id,
                    'buyable_ticket_id' => $gym_buyable_ticket->id,
                    'type' => $gym_buyable_ticket->type,
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
                    'buyable_ticket_id' => $gym_random_buyable_monthly_ticket->id,
                    'type' => $gym_random_buyable_monthly_ticket->type,
                ]);
            }
        }

        /* Enterances */
        $tickets = Ticket::all();
        $random_tickets = $tickets->random(rand(0, $tickets->count()));

        foreach ($random_tickets as $random_ticket) {
            $enterances = 1;
            if ($random_ticket->is_monthly()) {
                $enterances = rand(0, 15);
            }

            for ($i = 0; $i < $enterances; $i++) {
                $random_ticket_bought_date = CarbonImmutable::Create($random_ticket->bought);

                $random_enterance_date = $random_ticket_bought_date->add(rand(0, 20), 'day')->add(rand(0, 12), 'hour')->add(rand(0, 55), 'minute');

                $random_exit_date = CarbonImmutable::Create($random_enterance_date)->add(rand(1, 4), 'hour')->add(rand(11, 55), 'minute');

                $unused_lockers = Locker::all()->where('gym_id', $random_ticket->gym->id)->filter(function ($locker) {
                    return !$locker->is_used();
                })->values();

                $enterance = Enterance::factory()->create([
                    'gym_id' => $random_ticket->gym->id,
                    'user_id' => $random_ticket->user->id,
                    'ticket_id' => $random_ticket->id,
                    'enter' => $random_enterance_date,
                    'exit' => $random_exit_date,
                ]);
            }
        }

        /* Generate some usable tickets */
        for ($i = 0; $i < rand(0, 15); $i++) {
            $random_buyable_ticket = BuyableTicket::all()->random();
            $random_guest = User::all()->where('permission', 'user')->random();

            $rand_bought = CarbonImmutable::now()
                ->sub(rand(0, 15), 'days');

            $expiration = $rand_bought->add('30', 'days');

            Ticket::factory()->create([
                'user_id' => $random_guest->id,
                'gym_id' => $random_buyable_ticket->gym_id,
                'buyable_ticket_id' => $random_buyable_ticket->id,
                'type' => $random_buyable_ticket->type,
                'bought' => $rand_bought->format('Y-m-d'),
                'expiration' => $expiration->format('Y-m-d'),
            ]);
        }

        /* Random still in enterances */
        $ticket_count = Ticket::all()
            ->filter(function ($ticket) {
                return $ticket->useable();
            })
            ->count();

        $tickets = Ticket::all()
            ->filter(function ($ticket) {
                return $ticket->useable();
            })
            ->random(rand(0, $ticket_count));

        foreach ($tickets as $ticket) {
            if (Enterance::where('user_id', $ticket->user->id)->where('exit', null)->count() != 0) {
                break;
            }

            $random_locker = Locker::all()
                ->where('gym_id', $ticket->gym->id)
                ->where('gender', $ticket->user->gender)
                ->random();

            $random_enterance = CarbonImmutable::Now()
                ->subtract(rand(0, 3), 'hours')
                ->subtract(rand(0, 59), 'minutes');

            $enterance = Enterance::factory()->create([
                'gym_id' => $ticket->gym->id,
                'user_id' => $ticket->user->id,
                'ticket_id' => $ticket->id,
                'locker_id' => $random_locker->id,
                'enter' => $random_enterance,
                'exit' => null,
            ]);

            $random_locker->enterance_id = $enterance->id;
            $random_locker->save();
        }
    }
}
