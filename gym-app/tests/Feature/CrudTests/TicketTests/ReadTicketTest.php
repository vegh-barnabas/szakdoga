<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ReadTicketTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_ticket_routes()
    {
        // Create Admin
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Should fail because there is no user
        $response = $this->actingAs($admin)->get('tickets/edit-monthly/' . rand(0, 10));
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('tickets/edit-ticket/' . rand(0, 10));
        $response->assertStatus(403);

        // Create user
        $gym = Gym::factory()->create();
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
            'type' => 'one-time',
        ]);
        $guest = User::factory()->create([
            'permission' => 'user',
        ]);
        $ticket = Ticket::factory()->create([
            'user_id' => $guest->id,
            'gym_id' => $gym->id,
            'type_id' => $buyable_ticket->id,
        ]);

        $response = $this->actingAs($admin)->get('tickets/edit-monthly/' . $ticket->id);
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('tickets/edit-ticket/' . $ticket->id);
        $response->assertStatus(200);
    }

    public function test_receptionist_can_access_user_routes()
    {
        // Create Admin
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        $response = $this->actingAs($receptionist)->get('users/');
        $response->assertStatus(403);

        // Should fail because there is no user
        $response = $this->actingAs($receptionist)->get('users/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);
        // Create user
        $user = User::factory()->create();

        $response = $this->actingAs($receptionist)->get('users/' . $user->id . '/edit');
        $response->assertStatus(403);
    }

    public function test_guest_can_access_user_routes()
    {
        // Create Admin
        $guest = User::factory()->create([
            'permission' => 'user',
        ]);

        $response = $this->actingAs($guest)->get('users/');
        $response->assertStatus(403);

        // Should fail because there is no user
        $response = $this->actingAs($guest)->get('users/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);
        // Create user
        $user = User::factory()->create();

        $response = $this->actingAs($guest)->get('users/' . $user->id . '/edit');
        $response->assertStatus(403);
    }
}
