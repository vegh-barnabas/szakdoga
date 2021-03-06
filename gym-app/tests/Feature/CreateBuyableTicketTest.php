<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class CreateBuyableTicketTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_create_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Send request
        $response = $this->actingAs($admin)->post('/buyable-tickets', [
            'gym_id' => $gym->id,
            'name' => 'Test Ticket',
            'type' => 'one-time',
            'description' => 'Test ticket description',
            'quantity' => 32,
            'price' => 1024,
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if new buyable ticket is created
        $buyable_tickets = BuyableTicket::all()->first();

        $this->assertNotNull($buyable_tickets);
    }

    public function test_admin_cant_create_buyable_ticket_with_invalid_data()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Send request
        $response = $this->actingAs($admin)->post('/buyable-tickets', [
            'gym_id' => $gym->id + 1,
            'name' => '',
            'type' => 'invalid',
            'description' => 'Test ticket description',
            'quantity' => 32,
            'price' => 1024,
        ]);

        // Check if new buyable ticket is created
        $buyable_tickets = BuyableTicket::all()->first();

        $this->assertNull($buyable_tickets);
    }

    public function test_not_admin_cant_create_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create Users
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        $user = User::factory()->create([]);

        // Send request
        $response_1 = $this->actingAs($receptionist)->post('/buyable-tickets', [
            'gym_id' => $gym->id,
            'name' => 'Test Ticket',
            'type' => 'one-time',
            'description' => 'Test ticket description',
            'quantity' => 32,
            'price' => 1024,
        ]);

        // Check if response gives back error
        $response_1->assertStatus(403);

        // Send request
        $response_2 = $this->actingAs($user)->post('/buyable-tickets', [
            'gym_id' => $gym->id,
            'name' => 'Test Ticket',
            'type' => 'one-time',
            'description' => 'Test ticket description',
            'quantity' => 32,
            'price' => 1024,
        ]);

        // Check if response gives back error
        $response_2->assertStatus(403);

        // Check if no new buyable ticket is created
        $buyable_tickets = BuyableTicket::all()->first();

        $this->assertNull($buyable_tickets);
    }
}
