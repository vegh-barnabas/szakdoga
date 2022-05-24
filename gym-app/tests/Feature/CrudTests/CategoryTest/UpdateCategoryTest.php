<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{
    use TestingRefreshDatabase;
    public function test_admin_can_edit_category()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Get styles from Category
        $styles = Category::styles;

        // Create Category
        $category = Category::factory()->create([
            'style' => $styles[0],
        ]);

        // Send request
        $response = $this->actingAs($admin)->patch('/categories/' . $category->id, [
            'name' => 'After edit name',
            'style' => $styles[1],
        ]);

        // Check if response gives back redirect so the response is successful
        $response->assertStatus(302);

        // Check if new buyable ticket is created
        $modified_category = Category::all()->first();

        $this->assertNotEquals($category->style, $modified_category->style);
    }

    public function test_admin_cant_edit_category_with_invalid_data()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        // Get styles from Category
        $styles = Category::styles;

        // Create Category
        $category = Category::factory()->create([
            'name' => 'Before edit name',
            'style' => $styles[0],
        ]);

        // Send request
        $response = $this->actingAs($admin)->patch('/categories/' . $category->id, [
            'style' => 'invalid',
        ]);

        // Check if new buyable ticket is created
        $modified_category = Category::all()->first();
        // error_log(json_encode(Category::all()));

        $this->assertEquals($category->style, $modified_category->style);
    }

    public function test_not_admin_cant_edit_category()
    {
        // Create Gym
        $gym = Gym::factory()->create();

        // Create User
        $user = User::factory()->create();

        // Get styles from Category
        $styles = Category::styles;

        // Create Category
        $category = Category::factory()->create([
            'name' => 'Before edit name',
            'style' => $styles[0],
        ]);

        // Send request
        $response = $this->actingAs($user)->patch('/categories/' . $category->id, [
            'name' => 'After edit name',
            'style' => $styles[1],
        ]);

        // Check if new buyable ticket is created
        $modified_category = Category::all()->first();
        // error_log(json_encode(Category::all()));

        $this->assertEquals($category->style, $modified_category->style);
        $this->assertEquals($category->name, $modified_category->name);
    }
}
