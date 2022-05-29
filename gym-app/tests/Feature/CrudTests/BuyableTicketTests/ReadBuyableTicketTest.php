<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ReadBuyableTicketTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_buyable_ticket_routes()
    {
        // Create Admin
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('buyable-tickets/');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/buyable-tickets/create');
        $response->assertStatus(403); // Should fail because no gyms

        $gym = Gym::factory()->create();

        $response = $this->actingAs($admin)->get('/buyable-tickets/create');
        $response->assertStatus(200);

        // Should fail because there is no buyable ticket
        $response = $this->actingAs($admin)->get('buyable-tickets/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('buyable-tickets/' . rand(0, 10) . '/hide');
        $response->assertStatus(403);

        // Create gym for buyable ticket
        $gym = Gym::factory()->create();

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
        ]);

        $response = $this->actingAs($admin)->get('buyable-tickets/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('buyable-tickets/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(200);
    }

    public function test_guest_cant_access_buyable_ticket_routes()
    {
        // Create Guest
        $user = User::factory()->create([
            'permission' => 'user',
        ]);

        $response = $this->actingAs($user)->get('buyable-tickets/');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/buyable-tickets/create');
        $response->assertStatus(403);

        // Should fail because there is no buyable ticket
        $response = $this->actingAs($user)->get('buyable-tickets/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('buyable-tickets/' . rand(0, 10) . '/hide');
        $response->assertStatus(403);

        // Create gym for buyable ticket
        $gym = Gym::factory()->create();

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
        ]);

        // Should fail aswell because user doesn't have the right permissions
        $response = $this->actingAs($user)->get('buyable-tickets/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('buyable-tickets/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(403);

    }

    public function test_receptionist_can_access_their_buyable_ticket_routes()
    {
        // Create Receptionist
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        // Should fail because there is no buyable ticket
        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . rand(0, 10) . '/hide');
        $response->assertStatus(403);

        // Create gym for buyable ticket and set it for the receptionists workplace
        $gym = Gym::factory()->create();
        $receptionist->prefered_gym = $gym->id;

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
        ]);

        // Should fail aswell because user doesn't have the right permissions
        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(403);

        // Accessable routes for receptionists
        $response = $this->actingAs($receptionist)->get('buyable-tickets/');
        $response->assertStatus(200);
    }

    public function test_receptionist_cant_access_admin_buyable_ticket_routes()
    {
        // Create Receptionist
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        // Should fail because there is no buyable ticket
        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . rand(0, 10) . '/hide');
        $response->assertStatus(403);

        // Create gym for buyable ticket
        $gym = Gym::factory()->create();

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
        ]);

        // Should fail aswell because user doesn't have the right permissions
        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('buyable-tickets/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(403);

    }
}
