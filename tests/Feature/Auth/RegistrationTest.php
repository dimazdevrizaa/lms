<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_redirects_to_login(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect(route('login'));
    }

    public function test_registration_attempt_redirects_to_login(): void
    {
        $response = $this->post('/register', [
            'role' => 'siswa',
            'name' => 'Test Student',
            'email' => 'student@example.com',
            'identifier' => 'NIS-99999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
