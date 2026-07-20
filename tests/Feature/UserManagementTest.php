<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        $siswa = User::factory()->create(['role' => 'siswa', 'name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        $guru = User::factory()->create(['role' => 'guru', 'name' => 'Alisa Roza', 'email' => 'alisa@example.com']);

        // Search by name
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Budi']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Alisa Roza');

        // Search by email
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'alisa@']));
        $response->assertStatus(200);
        $response->assertSee('Alisa Roza');
        $response->assertDontSee('Budi Santoso');

        // Search by role
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'siswa']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Alisa Roza');
    }

    public function test_admin_can_sort_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Charlie', 'created_at' => now()]);
        // Avoid created_at matching exactly by sleeping or setting explicit timestamps
        $user1 = User::factory()->create(['role' => 'siswa', 'name' => 'Alice', 'created_at' => now()->subDays(2)]);
        $user2 = User::factory()->create(['role' => 'siswa', 'name' => 'Bob', 'created_at' => now()->subDays(1)]);

        // Sort by name ascending
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['sort' => 'name_asc']));
        $response->assertStatus(200);
        preg_match_all('/<strong style="color: var\(--primary\);">\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/strong>/', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        $this->assertEquals(['Alice', 'Bob', 'Charlie'], $names);

        // Sort by name descending
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['sort' => 'name_desc']));
        $response->assertStatus(200);
        preg_match_all('/<strong style="color: var\(--primary\);">\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/strong>/', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        $this->assertEquals(['Charlie', 'Bob', 'Alice'], $names);

        // Sort by latest created
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['sort' => 'latest']));
        $response->assertStatus(200);
        preg_match_all('/<strong style="color: var\(--primary\);">\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/strong>/', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        // Charlie (now) > Bob (-1 day) > Alice (-2 days)
        $this->assertEquals(['Charlie', 'Bob', 'Alice'], $names);

        // Sort by earliest created
        $response = $this->actingAs($admin)->get(route('admin.users.index', ['sort' => 'earliest']));
        $response->assertStatus(200);
        preg_match_all('/<strong style="color: var\(--primary\);">\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/strong>/', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        // Alice (-2 days) < Bob (-1 day) < Charlie (now)
        $this->assertEquals(['Alice', 'Bob', 'Charlie'], $names);
    }
}
