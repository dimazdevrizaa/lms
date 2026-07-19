<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_create_external_assignment(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'nip' => '1234567890',
            'phone' => '08123456789',
        ]);

        $class = SchoolClass::create(['name' => 'X IPA 1']);
        $subject = Subject::create(['name' => 'Matematika']);

        $response = $this->actingAs($user)->post(route('guru.assignments.store'), [
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'type' => 'external',
            'title' => 'Kuis Quizizz Persamaan Kuadrat',
            'description' => 'Kerjakan kuis berikut.',
            'due_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'quiz_url' => 'https://quizizz.com/join?gc=123456',
        ]);

        $response->assertRedirect(route('guru.assignments.index'));

        $this->assertDatabaseHas('assignments', [
            'teacher_id' => $teacher->id,
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'type' => 'external',
            'title' => 'Kuis Quizizz Persamaan Kuadrat',
            'quiz_url' => 'https://quizizz.com/join?gc=123456',
        ]);
    }

    public function test_teacher_can_create_online_assignment_with_images(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'nip' => '1234567890',
            'phone' => '08123456789',
        ]);

        $class = SchoolClass::create(['name' => 'X IPA 1']);
        $subject = Subject::create(['name' => 'Matematika']);

        $questionsJson = json_encode([
            [
                'type' => 'pilihan_ganda',
                'body' => 'Berapakah nilai x?',
                'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
                'points' => 5,
                'options' => [
                    ['label' => 'A', 'body' => '1', 'image' => '', 'is_correct' => true],
                    ['label' => 'B', 'body' => '2', 'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==', 'is_correct' => false],
                ]
            ]
        ]);

        $response = $this->actingAs($user)->post(route('guru.assignments.store'), [
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'type' => 'online',
            'title' => 'Tugas Matematika Gambar',
            'description' => 'Kerjakan tugas berikut.',
            'due_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'questions_json' => $questionsJson,
        ]);

        $response->assertRedirect(route('guru.assignments.index'));

        $this->assertDatabaseHas('assignments', [
            'title' => 'Tugas Matematika Gambar',
            'type' => 'online',
        ]);

        $this->assertDatabaseHas('questions', [
            'body' => 'Berapakah nilai x?',
            'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            'points' => 5,
        ]);

        $this->assertDatabaseHas('question_options', [
            'body' => '2',
            'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            'is_correct' => false,
        ]);
    }
}
