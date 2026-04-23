<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassSubjectTeacher;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function index(Request $request): View
    {
        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($teacherId, 403);

        // Jika ada filter class_id dan subject_id, tampilkan daftar pertemuan (view lama)
        if ($request->has('class_id') && $request->has('subject_id')) {
            $currentClass = SchoolClass::findOrFail($request->class_id);
            $currentSubject = Subject::findOrFail($request->subject_id);
            
            $meetings = Meeting::where('teacher_id', $teacherId)
                ->where('class_id', $request->class_id)
                ->where('subject_id', $request->subject_id)
                ->with(['schoolClass', 'subject'])
                ->orderBy('number', 'asc')
                ->get();

            return view('guru.meetings.list', compact('meetings', 'currentClass', 'currentSubject'));
        }

        // Tampilan awal: Grup Kelas & Mapel yang diajar
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        $assignments = collect([]);
        if ($activeYear) {
            $assignments = ClassSubjectTeacher::where('teacher_id', $teacherId)
                ->where('academic_year_id', $activeYear->id)
                ->with(['schoolClass', 'subject'])
                ->get();
        }

        // Hitung total meeting yang sudah dibuat untuk tiap kelommpok
        $meetingCounts = Meeting::where('teacher_id', $teacherId)
            ->select('class_id', 'subject_id')
            ->selectRaw('count(*) as total_meetings')
            ->groupBy('class_id', 'subject_id')
            ->get()
            ->keyBy(function($item) {
                return $item->class_id . '_' . $item->subject_id;
            });

        $meetingGroups = $assignments->map(function($assignment) use ($meetingCounts) {
            $key = $assignment->class_id . '_' . $assignment->subject_id;
            $assignment->total_meetings = $meetingCounts->has($key) ? $meetingCounts->get($key)->total_meetings : 0;
            return $assignment;
        })
        ->groupBy(function ($group) {
            return $group->schoolClass->major ?? 'Umum';
        })
        ->map(function ($majorGroup) {
            return $majorGroup->groupBy(function ($item) {
                return $item->subject->name ?? 'Tanpa Mata Pelajaran';
            });
        });

        return view('guru.meetings.index', compact('meetingGroups'));
    }

    public function create(Request $request): View
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

        // Auto-fill logic untuk pertemuan lanjutan
        $prefilledClassId = $request->query('class_id');
        $prefilledSubjectId = $request->query('subject_id');
        $nextNumber = 1;

        if ($prefilledClassId && $prefilledSubjectId) {
            $lastMeeting = Meeting::where('teacher_id', $teacher->id)
                ->where('class_id', $prefilledClassId)
                ->where('subject_id', $prefilledSubjectId)
                ->orderBy('number', 'desc')
                ->first();
            
            if ($lastMeeting) {
                $nextNumber = $lastMeeting->number + 1;
            }
        }

        return view('guru.meetings.create', compact('classes', 'subjects', 'prefilledClassId', 'prefilledSubjectId', 'nextNumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'number' => ['required', 'integer', 'min:1'],
        ]);

        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($teacherId, 403);

        Meeting::create(array_merge($data, ['teacher_id' => $teacherId]));

        return redirect()->route('guru.meetings.index')
            ->with('success', 'Pertemuan berhasil dibuat.');
    }

    public function edit(Meeting $meeting): View
    {
        abort_unless($meeting->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

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

        return view('guru.meetings.edit', compact('meeting', 'classes', 'subjects'));
    }

    public function update(Request $request, Meeting $meeting): RedirectResponse
    {
        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($meeting->teacher_id == $teacherId, 403);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'number' => ['required', 'integer', 'min:1'],
        ]);

        $meeting->update($data);

        return redirect()->route('guru.meetings.index')
            ->with('success', 'Pertemuan berhasil diperbarui.');
    }

    public function destroy(Meeting $meeting): RedirectResponse
    {
        abort_unless($meeting->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

        $meeting->delete();

        return redirect()->route('guru.meetings.index')
            ->with('success', 'Pertemuan berhasil dihapus.');
    }

    public function show(Meeting $meeting): View
    {
        abort_unless($meeting->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

        $meeting->load(['materials', 'assignments', 'attendance.details.student.user']);

        return view('guru.meetings.show', compact('meeting'));
    }
}
