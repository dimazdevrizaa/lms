<?php

namespace App\Http\Controllers\TataUsaha;

use App\Http\Controllers\Controller;
use App\Models\ClassSubjectTeacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
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
        $query = ClassSubjectTeacher::with(['schoolClass', 'subject', 'teacher.user'])->latest();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $assignments = $query->paginate(20);
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::with('teacher')->orderBy('name')->get();
        $teachers = Teacher::with('user')->orderBy('id')->get();

        return view('tatausaha.teaching-assignments.index', compact(
            'assignments', 'classes', 'subjects', 'teachers'
        ));
    }

    /**
     * Form tambah penugasan
     */
    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::with('teacher')->orderBy('name')->get();
        $teachers = Teacher::with('user')->orderBy('id')->get();

        return view('tatausaha.teaching-assignments.create', compact('classes', 'subjects', 'teachers'));
    }

    /**
     * Simpan penugasan baru
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ], [
            'class_id.required' => 'Pilih kelas.',
            'subject_id.required' => 'Pilih mata pelajaran.',
            'teacher_id.required' => 'Pilih guru.',
        ]);

        $exists = ClassSubjectTeacher::where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'subject_id' => 'Mapel ini sudah ditugaskan ke kelas tersebut. Edit penugasan yang ada jika ingin mengganti guru.',
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
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::with('teacher')->orderBy('name')->get();
        $teachers = Teacher::with('user')->orderBy('id')->get();

        return view('tatausaha.teaching-assignments.edit', compact(
            'teaching_assignment', 'classes', 'subjects', 'teachers'
        ));
    }

    /**
     * Update penugasan
     */
    public function update(Request $request, ClassSubjectTeacher $teaching_assignment): RedirectResponse
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        $exists = ClassSubjectTeacher::where('class_id', $data['class_id'])
            ->where('subject_id', $data['subject_id'])
            ->where('id', '!=', $teaching_assignment->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'subject_id' => 'Mapel ini sudah ditugaskan ke kelas tersebut.',
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

    /**
     * Bulk assign: seluruh guru mengambil kelas berdasarkan mapel yang diampu
     * Mapel IPA -> kelas IPA, Mapel IPS -> kelas IPS, Mapel Umum -> semua kelas
     */
    public function assignAll(): RedirectResponse
    {
        $classes = SchoolClass::all();
        $subjects = Subject::with('teacher')->get();
        $assigned = 0;

        foreach ($subjects as $subject) {
            if (!$subject->teacher_id) continue;

            foreach ($classes as $class) {
                $classMajor = $class->major ?? '';
                $subjectMajor = $subject->major ?? 'Umum';

                $match = match (strtoupper($subjectMajor)) {
                    'IPA' => in_array(strtoupper($classMajor), ['IPA']),
                    'IPS' => in_array(strtoupper($classMajor), ['IPS']),
                    'UMUM', '' => true,
                    default => strtoupper($classMajor) === strtoupper($subjectMajor),
                };

                if (!$match) continue;

                $existed = ClassSubjectTeacher::updateOrCreate(
                    ['class_id' => $class->id, 'subject_id' => $subject->id],
                    ['teacher_id' => $subject->teacher_id]
                );

                if ($existed->wasRecentlyCreated) {
                    $assigned++;
                }
            }
        }

        return redirect()->route('tatausaha.teaching-assignments.index')
            ->with('success', "Penugasan selesai. {$assigned} penugasan baru ditambahkan.");
    }
}
