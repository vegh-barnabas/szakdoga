<?php

namespace Tests\Feature;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ReadGymTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_gym_routes()
    {
        // Create Admin
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('gyms/');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('gyms/create');
        $response->assertStatus(200);

        // Should fail because there is no gym
        $response = $this->actingAs($admin)->get('gyms/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('gyms/' . rand(0, 10) . '/delete');
        $response->assertStatus(403);

        // Create gym
        $gym = Gym::factory()->create();

        $response = $this->actingAs($admin)->get('gyms/' . $gym->id . '/edit');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('gyms/' . $gym->id . '/delete');
        $response->assertStatus(200);
    }

    public function test_receptionist_cant_access_gym_routes()
    {
        // Create Admin
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        $response = $this->actingAs($receptionist)->get('gyms/');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('gyms/create');
        $response->assertStatus(403);

        // Should fail because there is no gym
        $response = $this->actingAs($receptionist)->get('gyms/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('gyms/' . rand(0, 10) . '/delete');
        $response->assertStatus(403);

        // Create gym
        $gym = Gym::factory()->create();

        $response = $this->actingAs($receptionist)->get('gyms/' . $gym->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('gyms/' . $gym->id . '/delete');
        $response->assertStatus(403);
    }

    public function test_guest_cant_access_gym_routes()
    {
        // Create Admin
        $guest = User::factory()->create([
            'permission' => 'user',
        ]);

        $response = $this->actingAs($guest)->get('gyms/');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('gyms/create');
        $response->assertStatus(403);

        // Should fail because there is no gym
        $response = $this->actingAs($guest)->get('gyms/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('gyms/' . rand(0, 10) . '/delete');
        $response->assertStatus(403);

        // Create gym
        $gym = Gym::factory()->create();

        $response = $this->actingAs($guest)->get('gyms/' . $gym->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('gyms/' . $gym->id . '/delete');
        $response->assertStatus(403);
    }
}
