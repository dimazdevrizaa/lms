<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_impersonate_student_and_stop(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);

        // 1. Initially logged in as admin
        $this->actingAs($admin);

        // 2. Perform impersonation POST request
        $response = $this->post(route('admin.impersonate.start', $student));

        // 3. Should redirect to student dashboard
        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertEquals($student->id, auth()->id());
        $this->assertTrue(session()->has('impersonate_original_id'));
        $this->assertEquals($admin->id, session('impersonate_original_id'));

        // 4. Perform stop impersonation POST request
        $response = $this->post(route('impersonate.stop'));

        // 5. Should redirect back to admin user list
        $response->assertRedirect(route('admin.users.index'));
        $this->assertEquals($admin->id, auth()->id());
        $this->assertFalse(session()->has('impersonate_original_id'));
    }

    public function test_admin_cannot_impersonate_another_admin(): void
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin1);

        $response = $this->post(route('admin.impersonate.start', $admin2));

        // Should return a validation / error redirect back
        $response->assertRedirect();
        $this->assertEquals($admin1->id, auth()->id());
        $this->assertFalse(session()->has('impersonate_original_id'));
    }

    public function test_non_admin_cannot_impersonate(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $teacher = User::factory()->create(['role' => 'guru']);

        $this->actingAs($student);

        // Call admin prefix route
        $response = $this->post(route('admin.impersonate.start', $teacher));

        // Should get unauthorized (middleware role:admin rejects it)
        $response->assertStatus(403);
        $this->assertEquals($student->id, auth()->id());
        $this->assertFalse(session()->has('impersonate_original_id'));
    }

    public function test_impersonating_user_profile_is_readonly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);

        $this->actingAs($admin);

        // Start impersonation
        $this->post(route('admin.impersonate.start', $student));

        // Attempt to update profile
        $response = $this->patch(route('profile.update'), [
            'name' => 'Hacker Student',
            'email' => 'hacker@student.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('error', 'Tidak dapat mengubah profil saat dalam sesi impersonasi.');

        // Verify database remains unchanged
        $student->refresh();
        $this->assertNotEquals('Hacker Student', $student->name);
    }
}
