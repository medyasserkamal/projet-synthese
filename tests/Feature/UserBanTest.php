<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserBanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin can toggle ban status on a user.
     */
    public function test_admin_can_ban_and_unban_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['is_banned' => false]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.users.toggle-ban', $user->id));

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->is_banned);

        // Toggle back to unban
        $response = $this->actingAs($admin)
            ->patch(route('admin.users.toggle-ban', $user->id));

        $response->assertRedirect();
        $this->assertFalse($user->fresh()->is_banned);
    }

    /**
     * Test that an admin cannot ban themselves.
     */
    public function test_admin_cannot_ban_themselves(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'is_banned' => false]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.users.toggle-ban', $admin->id));

        $response->assertSessionHasErrors();
        $this->assertFalse($admin->fresh()->is_banned);
    }

    /**
     * Test that a banned user cannot log in.
     */
    public function test_banned_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'banned@example.com',
            'password' => bcrypt('password123'),
            'is_banned' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'banned@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test that a logged-in user who becomes banned is logged out on next request.
     */
    public function test_logged_in_user_logged_out_when_banned(): void
    {
        $user = User::factory()->create(['is_banned' => false]);

        // Login the user
        $this->actingAs($user);

        // User can access home page
        $response = $this->get(route('home'));
        $response->assertOk();

        // Now ban the user directly in database
        $user->update(['is_banned' => true]);

        // Next request should log them out and redirect to login page
        $response = $this->get(route('home'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
