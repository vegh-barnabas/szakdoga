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
            'permission' => 'admin',
        ]);

        // Create Ticket Type
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'type' => 'one-time',
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
        $response = $this->actingAs($admin)->patch('/tickets/edit-ticket/' . $ticket->id, [
            'expiration' => '2011-11-11',
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $edited_ticket = Ticket::all()->where('id', $ticket->id)->first();

        $this->assertNotEquals($ticket->expiration, $edited_ticket->expiration);
    }

    public function test_admin_can_edit_monthly_ticket()
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
            'permission' => 'admin',
        ]);

        // Create Ticket Type
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'type' => 'monthly',
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
        $response = $this->actingAs($admin)->patch('/tickets/edit-monthly/' . $ticket->id, [
            'bought' => '2011-11-11',
            'expiration' => '2011-11-11',
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $edited_ticket = Ticket::all()->where('id', $ticket->id)->first();

        $this->assertNotEquals($ticket->bought, $edited_ticket->bought);
        $this->assertNotEquals($ticket->expiration, $edited_ticket->expiration);
    }

    public function test_not_admin_cant_edit_ticket()
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
        $admin = User::factory()->create();

        // Create Ticket Type
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'type' => 'one-time',
            ]
        );

        // Create User for the ticket
        $user = User::factory()->create();

        // Create the Ticket
        Ticket::factory()->create(
            [
                'user_id' => $user->id,
                'gym_id' => $gym->id,
                'type_id' => $buyable_ticket->id,
            ]
        );
        $ticket = Ticket::all()->first();

        // Send request
        $response = $this->actingAs($admin)->patch('/tickets/edit-ticket/' . $ticket->id, [
            'expiration' => '2011.11.11',
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check edited gym data is modified
        $edited_ticket = Ticket::all()->where('id', $ticket->id)->first();

        $this->assertEquals($ticket->expiration, $edited_ticket->expiration);
    }

    public function test_not_admin_cant_edit_monthly_ticket()
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
        $admin = User::factory()->create();

        // Create Ticket Type
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'type' => 'monthly',
            ]
        );

        // Create User for the ticket
        $user = User::factory()->create();

        // Create the Ticket
        Ticket::factory()->create(
            [
                'user_id' => $user->id,
                'gym_id' => $gym->id,
                'type_id' => $buyable_ticket->id,
            ]
        );
        $ticket = Ticket::all()->first();

        // Send request
        $response = $this->actingAs($admin)->patch('/tickets/edit-monthly/' . $ticket->id, [
            'bought' => '2011.11.11',
            'expiration' => '2011.11.11',
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check edited gym data is modified
        $edited_ticket = Ticket::all()->where('id', $ticket->id)->first();

        $this->assertEquals($ticket->bought, $edited_ticket->bought);
        $this->assertEquals($ticket->expiration, $edited_ticket->expiration);
    }

    public function test_admin_cant_edit_ticket_with_invalid_data()
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
            'permission' => 'admin',
        ]);

        // Create Ticket Type
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'type' => 'one-time',
            ]
        );

        // Create User for the ticket
        $user = User::factory()->create();

        // Create the Ticket
        Ticket::factory()->create(
            [
                'user_id' => $user->id,
                'gym_id' => $gym->id,
                'type_id' => $buyable_ticket->id,
            ]
        );
        $ticket = Ticket::all()->first();

        // Send request
        $response = $this->actingAs($admin)->patch('/tickets/edit-ticket/' . $ticket->id, [
            'expiration' => null,
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $edited_ticket = Ticket::all()->where('id', $ticket->id)->first();

        $this->assertEquals($ticket->expiration, $edited_ticket->expiration);
    }

    public function test_admin_cant_edit_monthly_ticket_with_invalid_data()
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
            'permission' => 'admin',
        ]);

        // Create Ticket Type
        $buyable_ticket = BuyableTicket::factory()->create(
            [
                'gym_id' => $gym->id,
                'type' => 'monthly',
            ]
        );

        // Create User for the ticket
        $user = User::factory()->create();

        // Create the Ticket
        Ticket::factory()->create(
            [
                'user_id' => $user->id,
                'gym_id' => $gym->id,
                'type_id' => $buyable_ticket->id,
            ]
        );
        $ticket = Ticket::all()->first();

        // Send request
        $response = $this->actingAs($admin)->patch('/tickets/edit-monthly/' . $ticket->id, [
            'bought' => null,
            'expiration' => null,
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $edited_ticket = Ticket::all()->where('id', $ticket->id)->first();

        $this->assertEquals($ticket->expiration, $edited_ticket->expiration);
        $this->assertEquals($ticket->bought, $edited_ticket->bought);
    }

}
