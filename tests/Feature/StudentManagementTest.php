<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_tata_usaha_can_search_students(): void
    {
        $tu = User::factory()->create(['role' => 'tatausaha', 'name' => 'TU Staff']);
        
        $class = SchoolClass::create(['name' => 'X IPA 1', 'major' => 'IPA']);

        $student1User = User::factory()->create(['role' => 'siswa', 'name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        $student1 = Student::create(['user_id' => $student1User->id, 'nis' => '11111', 'class_id' => $class->id]);

        $student2User = User::factory()->create(['role' => 'siswa', 'name' => 'Alisa Roza', 'email' => 'alisa@example.com']);
        $student2 = Student::create(['user_id' => $student2User->id, 'nis' => '22222', 'class_id' => $class->id]);

        // Search by name
        $response = $this->actingAs($tu)->get(route('tatausaha.students.index', ['search' => 'Budi']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Alisa Roza');

        // Search by email
        $response = $this->actingAs($tu)->get(route('tatausaha.students.index', ['search' => 'alisa@']));
        $response->assertStatus(200);
        $response->assertSee('Alisa Roza');
        $response->assertDontSee('Budi Santoso');

        // Search by NIS
        $response = $this->actingAs($tu)->get(route('tatausaha.students.index', ['search' => '22222']));
        $response->assertStatus(200);
        $response->assertSee('Alisa Roza');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_tata_usaha_can_sort_students(): void
    {
        $tu = User::factory()->create(['role' => 'tatausaha', 'name' => 'TU Staff']);
        $class = SchoolClass::create(['name' => 'X IPA 1', 'major' => 'IPA']);

        $student1User = User::factory()->create(['role' => 'siswa', 'name' => 'Alice']);
        $student1 = new Student(['user_id' => $student1User->id, 'nis' => '11111', 'class_id' => $class->id]);
        $student1->created_at = now()->subDays(2);
        $student1->save();

        $student2User = User::factory()->create(['role' => 'siswa', 'name' => 'Bob']);
        $student2 = new Student(['user_id' => $student2User->id, 'nis' => '22222', 'class_id' => $class->id]);
        $student2->created_at = now()->subDays(1);
        $student2->save();

        // Sort by name ascending
        $response = $this->actingAs($tu)->get(route('tatausaha.students.index', ['sort' => 'name_asc']));
        $response->assertStatus(200);
        preg_match_all('/<tr>\s*<td>[^<]+<\/td>\s*<td>\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/td>/i', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        $this->assertEquals(['Alice', 'Bob'], $names);

        // Sort by name descending
        $response = $this->actingAs($tu)->get(route('tatausaha.students.index', ['sort' => 'name_desc']));
        $response->assertStatus(200);
        preg_match_all('/<tr>\s*<td>[^<]+<\/td>\s*<td>\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/td>/i', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        $this->assertEquals(['Bob', 'Alice'], $names);

        // Sort by latest created
        $response = $this->actingAs($tu)->get(route('tatausaha.students.index', ['sort' => 'latest']));
        $response->assertStatus(200);
        preg_match_all('/<tr>\s*<td>[^<]+<\/td>\s*<td>\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/td>/i', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        $this->assertEquals(['Bob', 'Alice'], $names);

        // Sort by earliest created
        $response = $this->actingAs($tu)->get(route('tatausaha.students.index', ['sort' => 'earliest']));
        $response->assertStatus(200);
        preg_match_all('/<tr>\s*<td>[^<]+<\/td>\s*<td>\s*([^<\s]+(\s+[^<\s]+)*)\s*<\/td>/i', $response->getContent(), $matches);
        $names = array_map('trim', $matches[1]);
        $this->assertEquals(['Alice', 'Bob'], $names);
    }
}
