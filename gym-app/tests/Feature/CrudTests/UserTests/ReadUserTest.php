<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ReadUserTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_user_routes()
    {
        // Create Admin
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('users/');
        $response->assertStatus(200);

        // Should fail because there is no user
        $response = $this->actingAs($admin)->get('users/' . ($admin->id + 1) . '/edit');
        $response->assertStatus(403);
        // Create user
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->get('users/' . $user->id . '/edit');
        $response->assertStatus(200);
    }

    public function test_receptionist_can_access_user_routes()
    {
        // Create Admin
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        $response = $this->actingAs($receptionist)->get('users/');
        $response->assertStatus(403);

        // Should fail because there is no user
        $response = $this->actingAs($receptionist)->get('users/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);
        // Create user
        $user = User::factory()->create();

        $response = $this->actingAs($receptionist)->get('users/' . $user->id . '/edit');
        $response->assertStatus(403);
    }

    public function test_guest_can_access_user_routes()
    {
        // Create Admin
        $guest = User::factory()->create([
            'permission' => 'user',
        ]);

        $response = $this->actingAs($guest)->get('users/');
        $response->assertStatus(403);

        // Should fail because there is no user
        $response = $this->actingAs($guest)->get('users/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);
        // Create user
        $user = User::factory()->create();

        $response = $this->actingAs($guest)->get('users/' . $user->id . '/edit');
        $response->assertStatus(403);
    }
}
