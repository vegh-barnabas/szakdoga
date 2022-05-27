<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class DeleteGymTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_delete_gym()
    {
        // Pick some categories and get the IDs from it
        $categories = Category::factory(5)->create();
        $category_ids = $categories->pluck('id');

        // Get the first and last category ID
        $category_id = Arr::first($category_ids);

        // Create Gym
        $gym = Gym::factory()->create(
            [
                'name' => 'Test Gym',
                'address' => 'Test street 5.',
                'description' => 'Test description for the gym',
            ]
        );

        $gym->categories()->attach($categories);

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Send request
        $response = $this->actingAs($admin)->delete('/gyms/' . $gym->id);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $deleted_gym = Gym::all()->where('id', $gym->id)->first();

        $this->assertNull($deleted_gym);
    }

    public function test_user_cant_delete_gym()
    {
        // Pick some categories and get the IDs from it
        $categories = Category::factory(5)->create();
        $category_ids = $categories->pluck('id');

        // Get the first and last category ID
        $category_id = Arr::first($category_ids);

        // Create Gym
        $gym = Gym::factory()->create(
            [
                'name' => 'Test Gym',
                'address' => 'Test street 5.',
                'description' => 'Test description for the gym',
            ]
        );

        $gym->categories()->attach($categories);

        // Create User
        $user = User::factory()->create();

        // Send request
        $response = $this->actingAs($user)->delete('/gyms/' . $gym->id);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check edited gym data is modified
        $deleted_gym = Gym::all()->where('id', $gym->id)->first();

        $this->assertNotNull($deleted_gym);
    }

    public function test_receptionist_cant_delete_gym()
    {
        // Pick some categories and get the IDs from it
        $categories = Category::factory(5)->create();
        $category_ids = $categories->pluck('id');

        // Get the first and last category ID
        $category_id = Arr::first($category_ids);

        // Create Gym
        $gym = Gym::factory()->create(
            [
                'name' => 'Test Gym',
                'address' => 'Test street 5.',
                'description' => 'Test description for the gym',
            ]
        );

        $gym->categories()->attach($categories);

        // Create User
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
            'prefered_gym' => $gym->id,
        ]);

        // Send request
        $response = $this->actingAs($receptionist)->delete('/gyms/' . $gym->id);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check edited gym data is modified
        $deleted_gym = Gym::all()->where('id', $gym->id)->first();

        $this->assertNotNull($deleted_gym);
    }
}
