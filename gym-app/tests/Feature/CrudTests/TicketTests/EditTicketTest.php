<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class EditTicketTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_edit_ticket()
    {
        // Create Gym
        $gym = Gym::factory()->create(
            [
                'name' => 'Test Gym',
                'address' => 'Test street 5.',
                'description' => 'Test description for the gym',
            ]
        );

        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Create Ticket Type
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'type' => 'jegy',
            ]
        );

        // Create User for the ticket
        $user = User::factory()->create();

        // Create the Ticket
        $ticket = Ticket::factory()->create(
            [
                'user_id' => $user->id,
                'gym_id' => $gym->id,
                'type_id' => $buyable_ticket->id,
            ]
        );

        // Send request
        $response = $this->actingAs($admin)->patch('/ticket/edit/' . $ticket->id, [
            'expiration' => '2022.11.11',
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $edited_ticket = Ticket::all()->where('id', $ticket->id)->first();

        $this->assertNotEquals($ticket->expiration, $edited_ticket->expiration);
        $this->assertNotEquals($ticket->used, $edited_ticket->used);

    }
}
