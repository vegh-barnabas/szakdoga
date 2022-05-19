<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_delete_category()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Get styles from Category
        $styles = Category::styles;

        // Create Category
        $category = Category::factory()->create([
            'name' => 'Test category',
            'style' => Arr::random($styles),
        ]);

        // Send request
        $response = $this->actingAs($admin)->delete('/category/delete/' . $category->id);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Search for deleted category
        $deleted_category = Category::all()->where('id', $category->id)->first();

        $this->assertNull($deleted_category);
    }

    public function test_not_admin_cant_delete_category()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $user = User::factory()->create();

        // Get styles from Category
        $styles = Category::styles;

        // Create Category
        $category = Category::factory()->create([
            'name' => 'Test category',
            'style' => Arr::random($styles),
        ]);

        // Send request
        $response = $this->actingAs($user)->delete('/category/delete/' . $category->id);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Search for deleted category
        $deleted_category = Category::all()->where('id', $category->id)->first();

        $this->assertNotNull($deleted_category);
    }
}
