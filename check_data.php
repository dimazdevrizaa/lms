<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'guru@sma15padang.test')->first();
$teacher = App\Models\Teacher::where('user_id', $user->id)->first();
echo "User: {$user->name}, Teacher ID: {$teacher->id}\n";

// Check existing assignments
$count = App\Models\Assignment::where('teacher_id', $teacher->id)->count();
echo "Existing assignments: {$count}\n";

// Check the student for testing
$student = App\Models\User::where('email', 'siswa@sma15padang.test')->first();
$studentRecord = App\Models\Student::where('user_id', $student->id)->first();
echo "Student: {$student->name}, Student ID: {$studentRecord->id}, Class ID: {$studentRecord->class_id}\n";
