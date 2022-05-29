<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class HideBuyableTicketTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_hide_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'hidden' => false,
            ]);
        // Get the ID
        $buyable_ticket_id = BuyableTicket::all()->first()->id;

        // Send request
        $response = $this->actingAs($admin)->patch('/buyable-tickets/' . $buyable_ticket_id . '/hide');

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        $this->assertTrue(boolval($modified_ticket->hidden));
    }

    public function test_admin_can_show_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'hidden' => true,
            ]);
        // Get the ID
        $buyable_ticket_id = BuyableTicket::all()->first()->id;

        // Send request
        $response = $this->actingAs($admin)->patch('/buyable-tickets/' . $buyable_ticket_id . '/hide');

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        $this->assertFalse(boolval($modified_ticket->hidden));
    }

    public function test_not_admin_cant_hide_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $user = User::factory()->create();

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'hidden' => false,
            ]);
        // Get the ID
        $buyable_ticket_id = BuyableTicket::all()->first()->id;

        // Send request
        $response = $this->actingAs($user)->patch('/buyable-tickets/' . $buyable_ticket_id . '/hide');

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        $this->assertFalse(boolval($modified_ticket->hidden));
    }

    public function test_not_admin_cant_show_buyable_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $user = User::factory()->create();

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'hidden' => true,
            ]);
        // Get the ID
        $buyable_ticket_id = BuyableTicket::all()->first()->id;

        // Send request
        $response = $this->actingAs($user)->patch('/buyable-tickets/' . $buyable_ticket_id . '/hide');

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        $this->assertTrue(boolval($modified_ticket->hidden));
    }
}
