<?php

namespace Tests\Feature;

use App\Models\BuyableTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ViewTests extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_their_get_routes()
    {
        // Create Admin
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get('buyable-tickets/');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/buyable-tickets/create');
        $response->assertStatus(200);

        // Create buyable ticket
        $buyable_ticket = BuyableTicket::factory(1);

        $response = $this->actingAs($admin)->get('buyable-tickets/' . $buyable_ticket->id);
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('buyable-ticket/' . $buyable_ticket->id . '/edit');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('buyable-ticket/' . $buyable_ticket->id . '/hide');
        $response->assertStatus(200);
    }

    public function test_admin_cant_access_other_get_routes()
    {
    }

}
