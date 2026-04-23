<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Meeting;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassSubjectTeacher;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(): View
    {
        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($teacherId, 403);

        $assignments = Assignment::where('teacher_id', $teacherId)->latest()->paginate(20);

        return view('guru.assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if ($activeYear) {
            $assignedClassIds = ClassSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('class_id');
            $classes = SchoolClass::whereIn('id', $assignedClassIds)->orderBy('name')->get();
            
            $assignedSubjectIds = ClassSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('subject_id');
            $subjects = Subject::whereIn('id', $assignedSubjectIds)->orderBy('name')->get();
        } else {
            $classes = SchoolClass::orderBy('name')->get();
            $assignedSubjectIds = $teacher->teachingAssignments()->pluck('subject_id');
            $subjects = Subject::whereIn('id', $assignedSubjectIds)->orderBy('name')->get();
            if ($subjects->isEmpty()) {
                $subjects = Subject::orderBy('name')->get();
            }
        }

        $meetings = Meeting::where('teacher_id', $teacher->id)->orderBy('number')->get();

        return view('guru.assignments.create', compact('classes', 'subjects', 'meetings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'meeting_id' => ['nullable', 'exists:meetings,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($teacherId, 403);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        Assignment::create([
            'teacher_id' => $teacherId,
            'class_id' => $data['class_id'],
            'subject_id' => $data['subject_id'],
            'meeting_id' => $data['meeting_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_at' => $data['due_at'] ?? null,
            'file_path' => $filePath,
        ]);

        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }
    public function edit(Assignment $assignment): View
    {
        abort_unless($assignment->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        if ($activeYear) {
            $assignedClassIds = ClassSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('class_id');
            $classes = SchoolClass::whereIn('id', $assignedClassIds)->orderBy('name')->get();
            
            $assignedSubjectIds = ClassSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('subject_id');
            $subjects = Subject::whereIn('id', $assignedSubjectIds)->orderBy('name')->get();
        } else {
            $classes = SchoolClass::orderBy('name')->get();
            $assignedSubjectIds = $teacher->teachingAssignments()->pluck('subject_id');
            $subjects = Subject::whereIn('id', $assignedSubjectIds)->orderBy('name')->get();
            if ($subjects->isEmpty()) {
                $subjects = Subject::orderBy('name')->get();
            }
        }

        $meetings = Meeting::where('teacher_id', $teacher->id)->orderBy('number')->get();

        return view('guru.assignments.edit', compact('assignment', 'classes', 'subjects', 'meetings'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($assignment->teacher_id == $teacherId, 403);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'meeting_id' => ['nullable', 'exists:meetings,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if ($request->hasFile('file')) {
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            $data['file_path'] = $request->file('file')->store('assignments', 'public');
        }

        $assignment->update($data);

        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function show(Assignment $assignment): View
    {
        abort_unless($assignment->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

        $assignment->load(['submissions.student.user', 'schoolClass']);
        
        return view('guru.assignments.show', compact('assignment'));
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        abort_unless($assignment->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

        $assignment->delete();

        return redirect()->route('guru.assignments.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}

