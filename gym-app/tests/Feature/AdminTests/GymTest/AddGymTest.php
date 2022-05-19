<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class AddGymTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_create_gym()
    {
        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $categories = Category::factory(5)->create();

        // Send request
        $response = $this->actingAs($admin)->post('/gym/add', [
            'name' => 'Valid Gym',
            'address' => 'Valid street 5.',
            'description' => 'Totally valid description for the gym',
            'categories' => $categories->pluck('id'),
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if new buyable ticket is created
        $gym = Gym::all()->first();

        $this->assertNotNull($gym);
    }

    public function test_admin_cant_create_gym_with_invalid_data()
    {
        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $categories = Category::factory(5)->create();

        // Send request
        $response = $this->actingAs($admin)->post('/gym/add', [
            'name' => 'Valid Gym',
            'address' => 'Valid street 5.',
            'description' => 'Totally valid description for the gym',
            'categories' => [128],
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if new buyable ticket is created
        $gym = Gym::all()->first();

        $this->assertNull($gym);
    }

    public function test_not_admin_cant_create_gym()
    {
        // Create User
        $user = User::factory()->create();

        $categories = Category::factory(5)->create();

        // Send request
        $response = $this->actingAs($user)->post('/gym/add', [
            'name' => 'Valid Gym',
            'address' => 'Valid street 5.',
            'description' => 'Totally valid description for the gym',
            'categories' => $categories->pluck('id'),
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check if new buyable ticket is created
        $gym = Gym::all()->first();

        $this->assertNull($gym);
    }

    public function test_admin_cant_create_2_gyms_with_same_name()
    {
        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $categories = Category::factory(5)->create();

        // Send request
        $response = $this->actingAs($admin)->post('/gym/add', [
            'name' => 'Valid Gym',
            'address' => 'Valid street 5.',
            'description' => 'Totally valid description for the gym',
            'categories' => $categories->pluck('id'),
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if new buyable ticket is created
        $gym = Gym::all()->first();
        $gym_count = Gym::all()->count();

        $this->assertEquals($gym_count, 1);
        $this->assertNotNull($gym);

        // Send request
        $response = $this->actingAs($admin)->post('/gym/add', [
            'name' => 'Valid Gym',
            'address' => 'Valid street 5.',
            'description' => 'Totally valid description for the gym',
            'categories' => $categories->pluck('id'),
        ]);

        $gym_count = Gym::all()->count();

        $this->assertEquals($gym_count, 1);
    }
}
