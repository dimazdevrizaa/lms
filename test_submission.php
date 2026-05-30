<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\QuestionAnswer;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "         STUDENT SUBMISSION FLOW TEST\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

Auth::loginUsingId(4); // siswa@sma15padang.test
$student = Student::where('user_id', 4)->first();
$assignment = Assignment::with('questions.options')->find(3);

echo "Student: " . Auth::user()->name . " (ID: {$student->id})\n";
echo "Assignment: {$assignment->title} (ID: {$assignment->id})\n";
echo "Questions: {$assignment->questions->count()}\n\n";

// Simulate submitting answers
$q1 = $assignment->questions[0]; // pilihan_ganda
$q2 = $assignment->questions[1]; // isian_singkat 
$q3 = $assignment->questions[2]; // essay

$correctOption = $q1->options->where('is_correct', true)->first();

// Create request with all answers
$request = \Illuminate\Http\Request::create('/siswa/assignments/3/submit', 'POST', [
    '_token' => csrf_token(),
    'answers' => [
        $q1->id => ['selected_option_id' => $correctOption->id], // correct answer
        $q2->id => ['answer_text' => 'jakarta'], // correct (case insensitive)
        $q3->id => ['answer_text' => 'Bilangan prima adalah bilangan yang hanya bisa dibagi 1 dan dirinya sendiri.'],
    ],
]);

$response = app()->handle($request);
echo "Submit response status: " . $response->getStatusCode() . "\n\n";

// Check results
$submission = AssignmentSubmission::where('assignment_id', 3)
    ->where('student_id', $student->id)
    ->first();

if ($submission) {
    echo "✅ Submission created: #{$submission->id}\n";
    echo "   Score: " . ($submission->score ?? 'null (has essay, waiting for teacher)') . "\n";
    echo "   Submitted at: {$submission->submitted_at}\n\n";

    $answers = QuestionAnswer::where('assignment_submission_id', $submission->id)->get();
    echo "   Answers: {$answers->count()}\n\n";

    foreach ($answers as $ans) {
        $q = $assignment->questions->where('id', $ans->question_id)->first();
        echo "   Q{$q->order} ({$q->type}):\n";
        echo "      is_correct: " . ($ans->is_correct === null ? 'null (not graded)' : ($ans->is_correct ? 'true' : 'false')) . "\n";
        echo "      score: " . ($ans->score ?? 'null') . "\n";
        if ($ans->selected_option_id) echo "      selected_option_id: {$ans->selected_option_id}\n";
        if ($ans->answer_text) echo "      answer_text: " . substr($ans->answer_text, 0, 50) . "\n";
        echo "\n";
    }
} else {
    echo "❌ No submission found!\n";
    echo "   Response: " . substr($response->getContent(), 0, 500) . "\n";
}

// Test: Verify the results page now shows correctly
echo "Testing results page...\n";
$response = app()->handle(\Illuminate\Http\Request::create('/siswa/assignments/3', 'GET'));
echo "✅ Results page status: " . $response->getStatusCode() . "\n\n";

// Test: Teacher can see the submission
Auth::loginUsingId(3);
$response = app()->handle(\Illuminate\Http\Request::create('/guru/assignments/3', 'GET'));
echo "✅ Teacher show page status: " . $response->getStatusCode() . "\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "                ALL SUBMISSION TESTS COMPLETE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
