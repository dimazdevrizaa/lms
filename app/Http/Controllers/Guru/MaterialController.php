<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Meeting;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Schedule;
use App\Models\AcademicYear;
use Carbon\Carbon;

class MaterialController extends Controller
{
    public function dashboard(): View
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        abort_unless($teacher, 403);

        $materialsCount = Material::where('teacher_id', $teacher->id)->count();
        $assignmentsCount = \App\Models\Assignment::where('teacher_id', $teacher->id)->count();
        
        // Total pengumpulan dari semua tugas guru ini
        $submissionsCount = \App\Models\AssignmentSubmission::whereHas('assignment', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->count();

        // Pengumpulan yang belum dinilai (score is null)
        $pendingGradesCount = \App\Models\AssignmentSubmission::whereHas('assignment', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->whereNull('score')->count();

        // Ambil daftar kelas dari materi/tugas yang pernah dibuat
        $assignedClasses = SchoolClass::whereHas('materials', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orWhereHas('assignments', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->orWhereHas('studentGrades', function($q) use ($teacher) {
            // Karena student_grades tidak punya teacher_id, kita pakai subject_id
            // Filter grade yang mata pelajarannya diampu oleh guru ini
            $q->whereIn('subject_id', function($query) use ($teacher) {
                $query->select('subject_id')->from('class_subject_teacher')->where('teacher_id', $teacher->id);
            });
        })->withCount('students')
            ->get()
            ->groupBy('major');

        // Jadwal Mengajar Hari Ini
        $activeYear = AcademicYear::where('is_active', true)->first();
        $todaySchedules = collect();
        $todayIndo = '';

        if ($activeYear) {
            $daysIndo = [
                0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa',
                3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
            ];
            $todayIndo = $daysIndo[now()->dayOfWeek];

            if ($todayIndo !== 'Minggu') {
                $todaySchedules = Schedule::where('teacher_id', $teacher->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('day', $todayIndo)
                    ->with(['schoolClass', 'timeSlot', 'subject'])
                    ->get()
                    ->sortBy(function($schedule) {
                        return $schedule->timeSlot->slot_order;
                    });
            }
        }

        return view('guru.dashboard', compact(
            'materialsCount',
            'assignmentsCount',
            'submissionsCount',
            'pendingGradesCount',
            'assignedClasses',
            'todaySchedules',
            'todayIndo'
        ));
    }

    public function index(): View
    {
        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($teacherId, 403);

        $materials = Material::where('teacher_id', $teacherId)->latest()->paginate(20);

        return view('guru.materials.index', compact('materials'));
    }

    public function create(): View
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $classes = SchoolClass::orderBy('name')->get();
        
        $assignedSubjectIds = $teacher->teachingAssignments()->pluck('subject_id');
        $subjects = Subject::whereIn('id', $assignedSubjectIds)->orderBy('name')->get();
        
        if ($subjects->isEmpty()) {
            $subjects = Subject::orderBy('name')->get();
        }

        $meetings = Meeting::where('teacher_id', $teacher->id)->orderBy('number')->get();

        return view('guru.materials.create', compact('classes', 'subjects', 'meetings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'meeting_id' => ['nullable', 'exists:meetings,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'], // Max 10MB
        ]);

        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($teacherId, 403);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        Material::create([
            'teacher_id' => $teacherId,
            'class_id' => $data['class_id'],
            'subject_id' => $data['subject_id'],
            'meeting_id' => $data['meeting_id'],
            'title' => $data['title'],
            'content' => $data['content'] ?? null,
            'file_path' => $filePath,
        ]);

        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi berhasil diupload.');
    }
    public function edit(Material $material): View
    {
        abort_unless($material->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $classes = SchoolClass::orderBy('name')->get();
        
        $assignedSubjectIds = $teacher->teachingAssignments()->pluck('subject_id');
        $subjects = Subject::whereIn('id', $assignedSubjectIds)->orderBy('name')->get();
        if ($subjects->isEmpty()) {
            $subjects = Subject::orderBy('name')->get();
        }

        $meetings = Meeting::where('teacher_id', $teacher->id)->orderBy('number')->get();

        return view('guru.materials.edit', compact('material', 'classes', 'subjects', 'meetings'));
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $teacherId = Teacher::where('user_id', Auth::id())->value('id');
        abort_unless($material->teacher_id == $teacherId, 403);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'meeting_id' => ['nullable', 'exists:meetings,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $data['file_path'] = $request->file('file')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        abort_unless($material->teacher_id == Teacher::where('user_id', Auth::id())->value('id'), 403);

        $material->delete();

        return redirect()->route('guru.materials.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}

