<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentSubmissionController extends Controller
{
    public function index(): View
    {
        $student = Student::where('user_id', Auth::id())->first();

        $assignments = Assignment::query()
            ->when($student?->class_id, fn ($q, $classId) => $q->where('class_id', $classId))
            ->latest('due_at')
            ->get();

        return view('siswa.assignments.index', compact('assignments', 'student'));
    }

    public function store(Request $request, Assignment $assignment): RedirectResponse
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'answer_text' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // Max 5MB for student
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        if ($submission) {
            // Update existing
            if ($filePath && $submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
            $submission->update([
                'answer_text' => $data['answer_text'] ?? $submission->answer_text,
                'file_path' => $filePath ?? $submission->file_path,
                'submitted_at' => now(),
            ]);
        } else {
            // Create new
            AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
                'answer_text' => $data['answer_text'] ?? null,
                'file_path' => $filePath,
                'submitted_at' => now(),
            ]);
        }

        return back()->with('success', 'Tugas berhasil dikirim.');
    }
}

