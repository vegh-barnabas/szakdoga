<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class UpdateBuyableTicketTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_edit_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
            'quantity' => 64,
        ]);

        // Send request
        $response = $this->actingAs($admin)->patch('buyable-tickets/' . $buyable_ticket->id, [
            'quantity' => 32,
            'description' => 'Something Something Test Ticket description',
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket->id)->first();

        $this->assertNotEquals($buyable_ticket->quantity, $modified_ticket->quantity);
        $this->assertNotEquals($buyable_ticket->description, $modified_ticket->description);
    }

    public function test_admin_cant_edit_buyable_ticket_with_invalid_data()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
            'quantity' => 64,
        ]);
        // Get the ID
        $buyable_ticket_id = BuyableTicket::all()->first()->id;
        // error_log(json_encode($buyable_ticket_id));

        // Send request
        $this->actingAs($admin)->patch('/buyable-tickets/' . $buyable_ticket_id, [
            'quantity' => -1,
        ]);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        // error_log(json_encode($modified_ticket));
        $this->assertNotEquals($modified_ticket->quantity, -1);
    }

    public function test_not_admin_cant_edit_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $user = User::factory()->create();

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
            'quantity' => 64,
        ]);
        // Get the ID
        $buyable_ticket_id = BuyableTicket::all()->first()->id;
        // error_log(json_encode($buyable_ticket_id));

        // Send request
        $response = $this->actingAs($user)->patch('/buyable-tickets/' . $buyable_ticket_id, [
            'quantity' => 32,
            'description' => 'Totally valid description',
        ]);

        $response->assertStatus(403);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        // error_log(json_encode($modified_ticket));
        $this->assertNotEquals($modified_ticket->quantity, -1);
        $this->assertNotEquals($modified_ticket->quantity, 'Totally valid description');
    }
}
