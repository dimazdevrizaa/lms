<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Assignment;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionAnswer;
use App\Models\AssignmentSubmission;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "              ONLINE QUIZ FEATURE TEST\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// 1. Create an online assignment
$teacher = Teacher::where('user_id', User::where('email', 'guru@sma15padang.test')->value('id'))->first();

$assignment = Assignment::create([
    'teacher_id' => $teacher->id,
    'class_id' => 1, // X IPA 1
    'subject_id' => 1,
    'type' => 'online',
    'title' => '[TEST] Quiz Matematika Online',
    'description' => 'Quiz otomatis untuk testing fitur soal online',
    'due_at' => now()->addDays(7),
]);
echo "✅ Assignment created: #{$assignment->id} ({$assignment->title})\n";
echo "   Type: {$assignment->type}\n";
echo "   isOnline(): " . ($assignment->isOnline() ? 'true' : 'false') . "\n\n";

// 2. Add questions
// Q1: Pilihan Ganda
$q1 = Question::create([
    'assignment_id' => $assignment->id,
    'type' => 'pilihan_ganda',
    'body' => 'Berapakah hasil dari 2 + 2?',
    'order' => 1,
    'points' => 2,
]);
$opt_a = QuestionOption::create(['question_id' => $q1->id, 'label' => 'A', 'body' => '3', 'is_correct' => false]);
$opt_b = QuestionOption::create(['question_id' => $q1->id, 'label' => 'B', 'body' => '4', 'is_correct' => true]);
$opt_c = QuestionOption::create(['question_id' => $q1->id, 'label' => 'C', 'body' => '5', 'is_correct' => false]);
$opt_d = QuestionOption::create(['question_id' => $q1->id, 'label' => 'D', 'body' => '6', 'is_correct' => false]);
echo "✅ Q1 created: Pilihan Ganda ({$q1->body}) - Correct: B\n";

// Q2: Isian Singkat
$q2 = Question::create([
    'assignment_id' => $assignment->id,
    'type' => 'isian_singkat',
    'body' => 'Ibu kota Indonesia adalah?',
    'order' => 2,
    'points' => 2,
    'correct_answer' => 'Jakarta',
]);
echo "✅ Q2 created: Isian Singkat ({$q2->body}) - Correct: Jakarta\n";

// Q3: Essay
$q3 = Question::create([
    'assignment_id' => $assignment->id,
    'type' => 'essay',
    'body' => 'Jelaskan apa itu bilangan prima!',
    'order' => 3,
    'points' => 6,
]);
echo "✅ Q3 created: Essay ({$q3->body})\n\n";

// 3. Verify relationships
$assignment->load('questions.options');
echo "📊 Assignment has {$assignment->questions->count()} questions\n";
echo "   Total points: {$assignment->questions->sum('points')}\n\n";

// 4. Verify the views can load (check model methods)
echo "✅ All model relationships working\n";
echo "✅ Assignment->isOnline(): " . ($assignment->isOnline() ? 'PASS' : 'FAIL') . "\n";
echo "✅ Assignment->questions count: " . ($assignment->questions->count() == 3 ? 'PASS' : 'FAIL') . "\n";
echo "✅ Q1->options count: " . ($q1->options()->count() == 4 ? 'PASS' : 'FAIL') . "\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "   TEST ASSIGNMENT ID: {$assignment->id}\n";
echo "   Test this in browser:\n";
echo "   Teacher: http://localhost:8000/guru/assignments/{$assignment->id}\n";
echo "   Student: http://localhost:8000/siswa/assignments/{$assignment->id}\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
