<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Category;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\Enterance;
use App\Models\BuyableTicket;
use App\Models\Locker;

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

        /* Users */

        // Test User
        User::factory()->create([
            'name' => 'test',
            'email' => 'test' . '@br.hu',
            'password' => Hash::make('password'),
        ]);

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
        for ($i = 1; $i <= rand(10, 20); $i++) {
            User::factory()->create([
                'name' => 'user'. $i,
                'email' => 'user'. $i . '@br.hu',
                'password' => Hash::make('password'),
            ]);
        }

        /* Gyms */

        for ($i = 1; $i <= rand(1, 4); $i++) {
            $addr = ['Harap', 'Sajt', 'Kossuth', 'Petőfi', 'Arany', 'Gárdonyi'];

            Gym::factory()->create([
                'name' => $addr[$i] . " utcai edzőterem",
                'address' => $addr[$i] . " utca " . rand(1, 20)
            ]);
        }

        /* Categories */
        $styles = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
        $gyms = Gym::all();


        for ($i = 1; $i <= rand(4, 8); $i++) {
            $random_gym = $gyms->random();

            $category = Category::factory()->create([
                'gym_id' => $random_gym
            ]);
        }

        /* Buyable Tickets */
        for ($i = 1; $i <= rand(5, 15); $i++) {
        BuyableTicket::factory(['gym_id' => $gyms->random()])->create();
        }
        /* Tickets */
        $users = User::all();
        $buyable_tickets = BuyableTicket::all();

        for ($i = 1; $i <= rand(20, 40); $i++) {
            $random_user = $users->random();
            $random_gym = $gyms->random();
            $random_buyable_ticket = $buyable_tickets->random();

            $ticket = Ticket::factory()->create([
                'user_id' => $random_user,
                'gym_id' => $random_gym,
                'type_id' => $random_buyable_ticket,
            ]);
        }

        // Test user
        for ($i = 1; $i <= 10; $i++) {
            $random_gym = $gyms->random();
            $random_buyable_ticket = $buyable_tickets->random();

            $ticket = Ticket::factory()->create([
                'user_id' => 1,
                'gym_id' => $random_gym,
                'type_id' => $random_buyable_ticket,
            ]);
        }

        /* Enterances */
        $tickets = Ticket::all();
        foreach($tickets as $ticket) {
            if($ticket->type == 'bérlet') {
                for ($i = 0; $i < rand(0, 20); $i++) {
                    Enterance::factory()->create([
                        'user_id' => $ticket->user_id,
                        'ticket_id' => $ticket->id,
                        'gym_id' => $ticket->gym_id,
                    ]);
                }
            }
            else {
                if(rand(0, 1) == 0) // ticket used
                {
                    if(rand(0,1) == 0) // person is still in the gym
                    {
                        Enterance::factory()->create([
                            'user_id' => $ticket->user_id,
                            'ticket_id' => $ticket->id,
                            'gym_id' => $ticket->gym_id,
                            'exit' => null
                        ]);
                    }
                    else
                    {
                        Enterance::factory()->create([
                            'user_id' => $ticket->user_id,
                            'ticket_id' => $ticket->id,
                            'gym_id' => $ticket->gym_id,
                        ]);
                    }
                }
            }
        }

        /* Lockers */
        foreach($gyms as $gym) {
            for($i = 0; $i < rand(10, 20); $i++) {
                Locker::factory()->create([
                    'gym_id' => $gym->id,
                    'number' => $i
                ]);
            }
        }

        $enterances = Enterance::all();
        foreach($enterances as $index => $enterance) {
            if($enterance->exit == null) {
                Locker::factory()->create([
                    'gym_id' => $enterance->gym_id,
                    'user_id' => $enterance->user_id,
                    'gender' => $enterance->user->gender,
                    'number' => $index
                ]);
            }
        }
    }
}
