<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ReceptionistTests extends TestCase
{
    use TestingRefreshDatabase;

    public function test_receptionist_can_access_their_routes()
    {
        // Create gym
        $gym = Gym::factory()->create();

        // Create Receptionist
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
            'prefered_gym' => $gym->id,
        ]);

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('add-credits/');
        $response->assertStatus(200);

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('let-in/');
        $response->assertStatus(200);

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('let-out/');
        $response->assertStatus(200);

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('entered-users/');
        $response->assertStatus(200);

        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
        ]);

        $guest = User::factory()->create([
            'permission' => 'user',
        ]);

        $ticket = Ticket::factory()->create([
            'gym_id' => $gym->id,
            'buyable_ticket_id' => $buyable_ticket->id,
            'user_id' => $guest->id,
            'type' => $buyable_ticket->type,
        ]);

        $ticket->buyable_ticket()->associate($buyable_ticket);
    }

    public function test_guest_cant_access_receptionist_routes()
    {
        // Create gym
        $gym = Gym::factory()->create();

        // Create Receptionist
        $user = User::factory()->create([
            'permission' => 'user',
        ]);

        $response = $this->actingAs($user)->withSession(['gym' => $gym->id])->get('add-credits/');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->withSession(['gym' => $gym->id])->get('let-in/');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->withSession(['gym' => $gym->id])->get('let-out/');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->withSession(['gym' => $gym->id])->get('entered-users/');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->withSession(['gym' => $gym->id])->get('let-in/' . rand(0, 10));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->withSession(['gym' => $gym->id])->get('let-out/' . rand(0, 10));
        $response->assertStatus(403);
    }

    public function test_admin_cant_access_receptionist_routes()
    {
        // Create gym
        $gym = Gym::factory()->create();

        // Create Receptionist
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('add-credits/');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('let-in/');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('let-out/');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('entered-users/');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('let-in/' . rand(0, 10));
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('let-out/' . rand(0, 10));
        $response->assertStatus(403);
    }
}
