<?php

namespace App\Http\Controllers\TataUsaha;

use App\Http\Controllers\Controller;
use App\Models\ClassSubjectTeacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeachingAssignmentController extends Controller
{
    /**
     * Daftar penugasan guru mengajar mapel di kelas
     */
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Default ke tahun ajar aktif jika belum ada filter
        $selectedYearId = $request->filled('academic_year_id')
            ? $request->academic_year_id
            : ($activeYear?->id ?? null);

        $query = ClassSubjectTeacher::with(['schoolClass', 'subject', 'teacher.user', 'academicYear']);

        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Ambil semua dan group berdasarkan nama mata pelajaran
        $allAssignments = $query->get();
        $groupedBySubjectName = $allAssignments->groupBy(fn($a) => $a->subject->name ?? 'Tanpa Nama');

        // Urutkan berdasarkan nama mata pelajaran
        $orderedGroups = collect();
        foreach ($groupedBySubjectName->sortKeys() as $subjectName => $assignments) {
            $orderedGroups->put($subjectName, [
                'subject_name' => $subjectName,
                'assignments' => $assignments->sortBy(fn($a) => $a->schoolClass->name ?? ''),
            ]);
        }

        $classes = SchoolClass::orderBy('name')->get();
        $teachers = Teacher::with('user')->orderBy('id')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('tatausaha.teaching-assignments.index', compact(
            'orderedGroups', 'academicYears', 'classes', 'subjects', 'teachers', 'selectedYearId'
        ));
    }

    /**
     * Print-friendly view untuk cetak PDF via browser
     */
    public function printPdf(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $selectedYearId = $request->filled('academic_year_id')
            ? $request->academic_year_id
            : ($activeYear?->id ?? null);

        $query = ClassSubjectTeacher::with(['schoolClass', 'subject', 'teacher.user', 'academicYear']);

        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }
        if ($request->filled('subject_name')) {
            $query->whereHas('subject', function($q) use ($request) {
                // Gunakan like agar jika difilter kata pertama saja bisa matching (opsional)
                $q->where('name', 'like', $request->subject_name . '%');
            });
        }

        // Ambil semua dan group berdasarkan nama mata pelajaran
        $allAssignments = $query->get();
        $groupedBySubjectName = $allAssignments->groupBy(fn($a) => $a->subject->name ?? 'Tanpa Nama');

        // Urutkan berdasarkan nama mata pelajaran
        $orderedGroups = collect();
        foreach ($groupedBySubjectName->sortKeys() as $subjectName => $assignments) {
            $orderedGroups->put($subjectName, [
                'subject_name' => $subjectName,
                'assignments' => $assignments->sortBy(fn($a) => $a->schoolClass->name ?? ''),
            ]);
        }

        $academicYear = $selectedYearId ? AcademicYear::find($selectedYearId) : null;

        return view('tatausaha.teaching-assignments.print', compact('orderedGroups', 'academicYear'));
    }

    /**
     * Form tambah penugasan
     */
    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->orderBy('id')->get();

        return view('tatausaha.teaching-assignments.create', compact('academicYears', 'classes', 'subjects', 'teachers'));
    }

    /**
     * Simpan penugasan baru
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ], [
            'academic_year_id.required' => 'Pilih tahun ajar.',
            'class_id.required' => 'Pilih kelas.',
            'subject_id.required' => 'Pilih mata pelajaran.',
            'teacher_id.required' => 'Pilih guru.',
        ]);

        $exists = ClassSubjectTeacher::where('academic_year_id', $data['academic_year_id'])
            ->where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'subject_id' => 'Mapel ini sudah ditugaskan ke kelas tersebut pada tahun ajar ini. Edit penugasan yang ada jika ingin mengganti guru.',
            ]);
        }

        ClassSubjectTeacher::create($data);

        return redirect()->route('tatausaha.teaching-assignments.index')
            ->with('success', 'Penugasan guru berhasil ditambahkan.');
    }

    /**
     * Form edit penugasan
     */
    public function edit(ClassSubjectTeacher $teaching_assignment): View
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->orderBy('id')->get();

        return view('tatausaha.teaching-assignments.edit', compact(
            'teaching_assignment', 'academicYears', 'classes', 'subjects', 'teachers'
        ));
    }

    /**
     * Update penugasan
     */
    public function update(Request $request, ClassSubjectTeacher $teaching_assignment): RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        $exists = ClassSubjectTeacher::where('academic_year_id', $data['academic_year_id'])
            ->where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->where('id', '!=', $teaching_assignment->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'subject_id' => 'Mapel ini sudah ditugaskan ke kelas tersebut di tahun ajar yang sama.',
            ]);
        }

        $teaching_assignment->update($data);

        return redirect()->route('tatausaha.teaching-assignments.index')
            ->with('success', 'Penugasan berhasil diperbarui.');
    }

    /**
     * Hapus penugasan
     */
    public function destroy(ClassSubjectTeacher $teaching_assignment): RedirectResponse
    {
        $teaching_assignment->delete();

        return redirect()->route('tatausaha.teaching-assignments.index')
            ->with('success', 'Penugasan berhasil dihapus.');
    }


}
