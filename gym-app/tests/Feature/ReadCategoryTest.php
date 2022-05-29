<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase as TestingRefreshDatabase;
use Tests\TestCase;

class ReadCategoryTest extends TestCase
{
    use TestingRefreshDatabase;

    public function test_admin_can_access_category_routes()
    {
        // Create Admin
        $admin = User::factory()->create([
            'permission' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('categories/');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/categories/create');
        $response->assertStatus(200);

        // Should fail because there is no category
        $response = $this->actingAs($admin)->get('categories/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($admin)->get('categories/' . rand(0, 10) . '/delete');
        $response->assertStatus(403);

        // Create category ticket
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->get('categories/' . $category->id . '/edit');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('categories/' . $category->id . '/delete');
        $response->assertStatus(200);
    }

    public function test_guest_cant_access_category_routes()
    {
        // Create Guest
        $guest = User::factory()->create([
            'permission' => 'user',
        ]);

        $response = $this->actingAs($guest)->get('categories/');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('/categories/create');
        $response->assertStatus(403);

        // Should fail because there is no category
        $response = $this->actingAs($guest)->get('categories/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('categories/' . rand(0, 10) . '/delete');
        $response->assertStatus(403);

        // Create category ticket
        $category = Category::factory()->create();

        $response = $this->actingAs($guest)->get('categories/' . $category->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($guest)->get('categories/' . $category->id . '/delete');
        $response->assertStatus(403);
    }

    public function test_receptionist_cant_access_category_routes()
    {
        // Create Guest
        $receptionist = User::factory()->create([
            'permission' => 'receptionist',
        ]);

        $response = $this->actingAs($receptionist)->get('categories/');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('/categories/create');
        $response->assertStatus(403);

        // Should fail because there is no category
        $response = $this->actingAs($receptionist)->get('categories/' . rand(0, 10) . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('categories/' . rand(0, 10) . '/delete');
        $response->assertStatus(403);

        // Create category ticket
        $category = Category::factory()->create();

        $response = $this->actingAs($receptionist)->get('categories/' . $category->id . '/edit');
        $response->assertStatus(403);

        $response = $this->actingAs($receptionist)->get('categories/' . $category->id . '/delete');
        $response->assertStatus(403);
    }
}
