<?php

namespace App\Http\Controllers;

use App\Models\AssignmentSubmission;
use App\Models\SubmissionComment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class SubmissionCommentController extends Controller
{
    // ponytail: unified private comment store method for both roles to prevent code duplication
    public function store(Request $request, AssignmentSubmission $submission): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            $student = Student::where('user_id', $user->id)->firstOrFail();
            if ($submission->student_id !== $student->id) {
                abort(403, 'Akses ditolak.');
            }
        } elseif ($user->role === 'guru') {
            $teacher = Teacher::where('user_id', $user->id)->firstOrFail();
            if ($submission->assignment->teacher_id !== $teacher->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        SubmissionComment::create([
            'assignment_submission_id' => $submission->id,
            'user_id' => $user->id,
            'content' => $data['content'],
        ]);

        return back()->with('success', 'Komentar pribadi berhasil dikirim.');
    }
}
