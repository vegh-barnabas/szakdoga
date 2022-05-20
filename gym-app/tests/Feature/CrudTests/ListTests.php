<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class EditTicketTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_get_routes()
    {
        // // Create User
        // $admin = User::factory()->create([
        //     'is_admin' => true,
        // ]);

        // $response = $this->actingAs($admin)->get('/home');
        // $response->assertStatus(200);

        // $response = $this->actingAs($admin)->get('/settings');
        // $response->assertStatus(403);

        // $response = $this->actingAs($admin)->get('/');
        // $response->assertStatus(200);

        // $response = $this->actingAs($admin)->get('/buy');
        // $response->assertStatus(200);

        // $response = $this->actingAs($admin)->get('/tickets');
        // $response->assertStatus(200);

        // $response = $this->actingAs($admin)->get('/stats');
        // $response->assertStatus(200);
    }

}
