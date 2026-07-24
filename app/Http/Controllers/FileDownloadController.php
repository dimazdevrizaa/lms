<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    /**
     * Download or stream the teacher's assignment questions PDF.
     */
    public function downloadAssignment(Assignment $assignment)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin can access all assignments
        } elseif ($user->role === 'guru') {
            $teacherId = Teacher::where('user_id', $user->id)->value('id');
            abort_unless($assignment->teacher_id == $teacherId, 403, 'Unauthorized.');
        } elseif ($user->role === 'siswa') {
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $classId = $assignment->class_id ?? $assignment->meeting?->class_id;
            abort_unless($classId == $student->class_id, 403, 'Unauthorized.');
        } else {
            abort(403, 'Unauthorized.');
        }

        $filePath = $assignment->file_path;
        if (!$filePath || str_contains($filePath, '..')) {
            abort(404, 'File not found.');
        }

        if (Storage::disk('local')->exists($filePath)) {
            return response()->file(Storage::disk('local')->path($filePath));
        } elseif (Storage::disk('public')->exists($filePath)) {
            return response()->file(Storage::disk('public')->path($filePath));
        }

        abort(404, 'File not found.');
    }

    /**
     * Download or stream the student's submission PDF.
     */
    public function downloadSubmission(AssignmentSubmission $submission)
    {
        $user = Auth::user();
        $assignment = $submission->assignment;

        if ($user->role === 'admin') {
            // Admin can access all student submissions
        } elseif ($user->role === 'guru') {
            $teacherId = Teacher::where('user_id', $user->id)->value('id');
            abort_unless($assignment->teacher_id == $teacherId, 403, 'Unauthorized.');
        } elseif ($user->role === 'siswa') {
            $student = Student::where('user_id', $user->id)->firstOrFail();
            abort_unless($submission->student_id == $student->id, 403, 'Unauthorized.');
        } else {
            abort(403, 'Unauthorized.');
        }

        $filePath = $submission->file_path;
        if (!$filePath || str_contains($filePath, '..')) {
            abort(404, 'File not found.');
        }

        if (Storage::disk('local')->exists($filePath)) {
            return response()->file(Storage::disk('local')->path($filePath));
        } elseif (Storage::disk('public')->exists($filePath)) {
            return response()->file(Storage::disk('public')->path($filePath));
        }

        abort(404, 'File not found.');
    }

    /**
     * View or stream a material PDF.
     */
    public function downloadMaterial(Material $material)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin can access all materials
        } elseif ($user->role === 'guru') {
            $teacherId = Teacher::where('user_id', $user->id)->value('id');
            abort_unless($material->teacher_id == $teacherId, 403, 'Unauthorized.');
        } elseif ($user->role === 'siswa') {
            $student = Student::where('user_id', $user->id)->firstOrFail();
            $classId = $material->class_id ?? $material->meeting?->class_id;
            abort_unless($classId == $student->class_id, 403, 'Unauthorized.');
        } else {
            abort(403, 'Unauthorized.');
        }

        $filePath = $material->file_path;
        if (!$filePath || str_contains($filePath, '..')) {
            abort(404, 'File not found.');
        }

        if (Storage::disk('public')->exists($filePath)) {
            return response()->file(Storage::disk('public')->path($filePath));
        } elseif (Storage::disk('local')->exists($filePath)) {
            return response()->file(Storage::disk('local')->path($filePath));
        }

        abort(404, 'File not found.');
    }
}
