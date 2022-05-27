<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class GuestTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_guest_can_access_their_routes()
    {
        // Create Guest
        $guest = User::factory()->create([
            'permission' => 'user',
            'credits' => 99999,
        ]);

        // Create gym for session
        $gym = Gym::factory()->create();

        $response = $this->actingAs($guest)->withSession(['gym' => $gym->id])->get('statistics/');
        $response->assertStatus(200);

        $response = $this->actingAs($guest)->withSession(['gym' => $gym->id])->get('tickets/');
        $response->assertStatus(200);

        $response = $this->actingAs($guest)->withSession(['gym' => $gym->id])->get('buy-ticket/');
        $response->assertStatus(200);

        // Should fail because there is no buyable ticket
        $response = $this->actingAs($guest)->withSession(['gym' => $gym->id])->get('buy-ticket/' . rand(0, 10));
        $response->assertStatus(403);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
            'hidden' => false,
        ]);

        $response = $this->actingAs($guest)->withSession(['gym' => $gym->id])->get('buy-ticket/' . $buyable_ticket->id);
        $response->assertStatus(200);
    }

    public function test_receptionist_cant_access_guest_routes()
    {
        // Create Guest
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        // Create gym for session
        $gym = Gym::factory()->create();

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('statistics/');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('tickets/');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('buy-ticket/');
        $response->assertStatus(403);

        // Should fail because there is no buyable ticket
        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('buy-ticket/' . rand(0, 10));
        $response->assertStatus(403);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
        ]);

        $response = $this->actingAs($receptionist)->withSession(['gym' => $gym->id])->get('buy-ticket/' . $buyable_ticket->id);
        $response->assertStatus(403);
    }

    public function test_admin_cant_access_guest_routes()
    {
        // Create Guest
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Create gym for session
        $gym = Gym::factory()->create();

        $response = $this->actingAs($admin)->get('statistics/');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('tickets/');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('buy-ticket/');
        $response->assertStatus(403);

        // Should fail because there is no buyable ticket
        $response = $this->actingAs($admin)->get('buy-ticket/' . rand(0, 10));
        $response->assertStatus(403); // There isn't a gym in session

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory()->create([
            'gym_id' => $gym->id,
        ]);

        $response = $this->actingAs($admin)->get('buy-ticket/' . $buyable_ticket->id);
        $response->assertStatus(403);
    }
}
