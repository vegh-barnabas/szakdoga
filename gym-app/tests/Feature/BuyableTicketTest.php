<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\User;
use Tests\TestCase;

class BuyableTicketTest extends TestCase
{
    /* Create */
    public function test_admin_can_create_buyable_ticket()
    {
        $this->artisan('migrate:fresh');

        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Send request
        $response = $this->actingAs($admin)->post('/buyable/add', [
            'gym_id' => $gym->id,
            'name' => 'Test Ticket',
            'type' => 'jegy',
            'description' => 'Test ticket description',
            'quantity' => 32,
            'price' => 1024,
        ]);

        // Check if response gives back redirect so the response is successfull
        $response->assertStatus(302);

        // Check if new buyable ticket is created
        $buyable_tickets = BuyableTicket::all()->first();

        $this->assertNotNull($buyable_tickets);
    }

    public function test_admin_cant_create_buyable_ticket_with_invalid_data()
    {
        $this->artisan('migrate:fresh');

        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Send request
        $response = $this->actingAs($admin)->post('/buyable/add', [
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
        $this->artisan('migrate:fresh');

        // Create Gym
        $gym = Gym::factory()->create();

        // Create Users
        $receptionist = User::factory()->create([
            'is_receptionist' => true,
        ]);

        $user = User::factory()->create([]);

        // Send request
        $response_1 = $this->actingAs($receptionist)->post('/buyable/add', [
            'gym_id' => $gym->id,
            'name' => 'Test Ticket',
            'type' => 'jegy',
            'description' => 'Test ticket description',
            'quantity' => 32,
            'price' => 1024,
        ]);

        // Check if response gives back error
        $response_1->assertStatus(403);

        // Send request
        $response_2 = $this->actingAs($user)->post('/buyable/add', [
            'gym_id' => $gym->id,
            'name' => 'Test Ticket',
            'type' => 'jegy',
            'description' => 'Test ticket description',
            'quantity' => 32,
            'price' => 1024,
        ]);

        // Check if response gives back error
        $response_2->assertStatus(403);

        // Check if no new buyable ticket is created
        $buyable_tickets = BuyableTicket::all()->first();

        // error_log(json_encode($buyable_tickets));
        $this->assertNull($buyable_tickets);
    }

    /* Edit */
    public function test_admin_can_edit_buyable_ticket()
    {
        $this->artisan('migrate:fresh');

        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
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
        $response = $this->actingAs($admin)->patch('/buyable/edit/' . $buyable_ticket_id, [
            'quantity' => 32,
            'description' => 'Something Something Test Ticket description',
        ]);

        // Check if response gives back redirect so the response is successfull
        $response->assertStatus(302);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        // error_log(json_encode($modified_ticket));
        $this->assertEquals($modified_ticket->description, 'Something Something Test Ticket description');
        $this->assertEquals($modified_ticket->quantity, 32);
    }

    public function test_admin_cant_edit_buyable_ticket_with_invalid_data()
    {
        $this->artisan('migrate:fresh');

        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
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
        $this->actingAs($admin)->patch('/buyable/edit/' . $buyable_ticket_id, [
            'quantity' => -1,
        ]);

        // Check if the buyable ticket is modified
        $modified_ticket = BuyableTicket::all()->where('id', $buyable_ticket_id)->first();
        // error_log(json_encode($modified_ticket));
        $this->assertNotEquals($modified_ticket->quantity, -1);
    }

    public function test_not_admin_cant_edit_buyable_ticket()
    {
        $this->artisan('migrate:fresh');

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
        $response = $this->actingAs($user)->patch('/buyable/edit/' . $buyable_ticket_id, [
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
