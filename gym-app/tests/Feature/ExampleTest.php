<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_the_login_screen()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_register_unique_name()
    {
        $user1 = User::make([
            'name' => 'Teszt1',
            'email' => 'teszt1@br.hu',
        ]);

        $user2 = User::make([
            'name' => 'Teszt2',
            'email' => 'teszt2@br.hu',
        ]);

        $this->assertTrue($user1->name != $user2->name);
    }
}
