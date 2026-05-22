<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ======================
    // REGISTER
    // ======================

    public function test_register_page_can_be_accessed()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'username'              => 'testuser',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role'  => 'customer',
        ]);
    }

    public function test_register_fails_with_duplicate_email()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'username'              => 'testuser2',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_register_fails_with_mismatched_password()
    {
        $response = $this->post('/register', [
            'username'              => 'testuser',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // ======================
    // LOGIN
    // ======================

    public function test_login_page_can_be_accessed()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_customer_can_login()
    {
        $user = User::factory()->create([
            'email'    => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'customer',
        ]);

        $response = $this->post('/login', [
            'email'    => 'customer@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_owner_redirected_to_dashboard_after_login()
    {
        $owner = User::factory()->create([
            'email'    => 'owner@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'owner',
        ]);

        $response = $this->post('/login', [
            'email'    => 'owner@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_login_fails_with_wrong_password()
    {
        User::factory()->create([
            'email'    => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    // ======================
    // LOGOUT
    // ======================

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_already_logged_in_user_redirected_from_login_page()
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/');
    }
}