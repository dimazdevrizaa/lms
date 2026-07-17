<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_students_can_register(): void
    {
        $response = $this->post('/register', [
            'role' => 'siswa',
            'name' => 'Test Student',
            'email' => 'student@example.com',
            'identifier' => 'NIS-99999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('siswa.dashboard'));

        $this->assertDatabaseHas('classes', [
            'name' => 'Kelas Digital',
        ]);
        $student = \App\Models\Student::where('nis', 'NIS-99999')->first();
        $this->assertNotNull($student);
        $this->assertNotNull($student->class_id);
        $this->assertEquals('Kelas Digital', $student->schoolClass->name);
    }

    public function test_new_teachers_can_register(): void
    {
        $response = $this->post('/register', [
            'role' => 'guru',
            'name' => 'Test Teacher',
            'email' => 'teacher@example.com',
            'identifier' => 'NIP-99999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('guru.dashboard'));
    }
}
