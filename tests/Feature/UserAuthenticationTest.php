<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class UserAuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserRegistration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'cklong2k+5@gmail.com',
            'password' => Hash::make('password123'),
        ];

        $response = $this->post('api/v1/register', $userData);

        $response->assertStatus(201); // Assuming successful registration redirects to another page

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'cklong2k+5@gmail.com',
        ]);
    }

    public function testUserLogin()
    {
        $user = User::factory(User::class)->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->post('api/v1/login', $credentials);

        $response->assertStatus(200); // Assuming successful login redirects to another page
        $this->assertAuthenticatedAs($user);
    }
}
