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

    public function test_teacher_and_student_can_upload_doc_docx_xls_xlsx_files(): void
    {
        $teacherUser = User::factory()->create(['role' => 'guru']);
        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'nip' => '1234567890',
            'phone' => '08123456789',
        ]);

        $class = SchoolClass::create(['name' => 'X IPA 1']);
        $subject = Subject::create(['name' => 'Matematika']);

        // 1. Teacher uploads docx assignment
        $fakeDocx = \Illuminate\Http\UploadedFile::fake()->create('assignment.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $response = $this->actingAs($teacherUser)->post(route('guru.assignments.store'), [
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'type' => 'pdf', // DB type name is still pdf but labeled as Document
            'title' => 'Tugas Menulis Laporan',
            'description' => 'Kerjakan di docx.',
            'due_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'file' => $fakeDocx,
        ]);

        $response->assertRedirect(route('guru.assignments.index'));
        $assignment = Assignment::where('title', 'Tugas Menulis Laporan')->first();
        $this->assertNotNull($assignment);
        $this->assertStringEndsWith('.docx', $assignment->file_path);

        // 2. Student uploads xlsx submission
        $studentUser = User::factory()->create(['role' => 'siswa']);
        $student = Student::create([
            'user_id' => $studentUser->id,
            'nis' => '111222',
            'class_id' => $class->id,
        ]);

        $fakeXlsx = \Illuminate\Http\UploadedFile::fake()->create('answer.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response = $this->actingAs($studentUser)->post(route('siswa.assignments.submit', $assignment), [
            'answer_text' => 'Ini jawaban saya.',
            'file' => $fakeXlsx,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assignment_submissions', [
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'answer_text' => 'Ini jawaban saya.',
        ]);
    }
}
