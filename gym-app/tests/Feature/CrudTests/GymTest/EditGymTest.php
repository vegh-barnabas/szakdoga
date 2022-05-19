<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class EditGymTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_edit_gym()
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
            'is_admin' => true,
        ]);

        // Send request
        $response = $this->actingAs($admin)->patch('/gym/edit/' . $gym->id, [
            'name' => 'Valid Gym',
            'address' => 'Valid street 5.',
            'description' => 'Totally valid description for the gym',
            'categories' => [$category_id],
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $edited_gym = Gym::all()->first();

        $this->assertNotEquals($gym->name, $edited_gym->name);
        $this->assertNotEquals($gym->address, $edited_gym->address);
        $this->assertNotEquals($gym->description, $edited_gym->description);
        $this->assertNotEquals($gym->categories, $edited_gym->categories);
    }

    public function test_admin_cant_edit_gym_with_invalid_data()
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
            'is_admin' => true,
        ]);

        // Send request
        $response = $this->actingAs($admin)->patch('/gym/edit/' . $gym->id, [
            'name' => null,
            'address' => null,
            'description' => null,
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check edited gym data is modified
        $edited_gym = Gym::all()->first();

        $this->assertEquals($gym->name, $edited_gym->name);
        $this->assertEquals($gym->address, $edited_gym->address);
        $this->assertEquals($gym->description, $edited_gym->description);
    }

    public function test_user_cant_edit_gym()
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
        $response = $this->actingAs($user)->patch('/gym/edit/' . $gym->id, [
            'name' => 'Valid Gym',
            'address' => 'Valid street 5.',
            'description' => 'Totally valid description for the gym',
            'categories' => [$category_id],
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check edited gym data is modified
        $edited_gym = Gym::all()->first();

        $this->assertEquals($gym->name, $edited_gym->name);
        $this->assertEquals($gym->address, $edited_gym->address);
        $this->assertEquals($gym->description, $edited_gym->description);
    }
}
