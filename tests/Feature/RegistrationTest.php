<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that registering with a standard email creates a regular user.
     */
    public function test_standard_user_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->is_verified);
    }

    /**
     * Test that registering with a @zarticle.com email creates an admin user.
     */
    public function test_admin_user_registration_via_zarticle_email(): void
    {
        $response = $this->post('/register', [
            'name' => 'Admin User',
            'email' => 'alex@zarticle.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $user = User::where('email', 'alex@zarticle.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->is_verified);
    }
}
