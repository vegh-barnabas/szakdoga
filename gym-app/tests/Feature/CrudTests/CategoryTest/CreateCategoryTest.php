<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_create_category()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Get styles from Category
        $styles = Category::styles;

        // Send request
        $response = $this->actingAs($admin)->post('/category/add', [
            'name' => 'Test Category',
            'style' => Arr::random($styles),
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if new buyable ticket is created
        $category = Category::all()->first();
        // error_log(json_encode(Category::all()));

        $this->assertNotNull($category);
    }

    public function test_admin_cant_create_category_with_invalid_data()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Get styles from Category
        $styles = Category::styles;

        // Send request
        $response = $this->actingAs($admin)->post('/category/add', [
            'style' => 'invalid',
        ]);

        // Check if new buyable ticket is created
        $category = Category::all()->first();
        // error_log(json_encode(Category::all()));

        $this->assertNull($category);
    }

    public function test_not_admin_cant_create_category()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $user = User::factory()->create();

        // Get styles from Category
        $styles = Category::styles;

        // Send request
        $response = $this->actingAs($user)->post('/category/add', [
            'name' => 'Test Category',
            'style' => Arr::random($styles),
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(403);

        // Check if new buyable ticket is created
        $category = Category::all()->first();
        // error_log(json_encode(Category::all()));

        $this->assertNull($category);
    }

}
