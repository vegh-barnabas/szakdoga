<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ViewTests extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_their_get_routes()
    {
        // Create gym
        $gym = Gym::factory()->create();

        // Create Admin
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('buyable-tickets/');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/buyable-tickets/create');
        $response->assertStatus(200);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create(['gym_id' => $gym->id]);

        $response = $this->actingAs($admin)->get('buyable-tickets/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('buyable-tickets/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(200);
    }

    public function test_guest_cant_access_admin_get_routes()
    {
        // Create gym
        $gym = Gym::factory()->create();

        // Create Guest
        $guest = User::factory()->create([
            'permission' => 'user',
        ]);

        $response = $this->actingAs($guest)->get('buyable-tickets/');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('/buyable-tickets/create');
        $response->assertStatus(403);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create(['gym_id' => $gym->id]);

        $response = $this->actingAs($guest)->get('buyable-tickets/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('buyable-tickets/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(403);
    }

    public function test_receptionist_cant_access_admin_get_routes()
    {
        // Create gym
        $gym = Gym::factory()->create();

        // Create Receptionist
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
            'prefered_gym' => $gym->id,
        ]);

        $response = $this->actingAs($receptionist)->get('buyable-tickets/'); // Common route
        $response->assertStatus(200);

        $response = $this->actingAs($receptionist)->get('/buyable-tickets/create');
        $response->assertStatus(403);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create(['gym_id' => $gym->id]);

        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(403);

    }

}
