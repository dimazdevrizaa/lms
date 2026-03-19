<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ClassSubjectTeacher;
use App\Models\Material;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentMaterialController extends Controller
{
    /**
     * Menampilkan daftar Mata Pelajaran yang tersedia untuk kelas siswa.
     * Berdasarkan penugasan guru mengambil kelas (class_subject_teacher).
     * Fallback: jika belum ada penugasan, ambil dari meeting/material yang sudah ada.
     */
    public function subjects(): View
    {
        $student = Student::where('user_id', Auth::id())->first();
        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        // Prioritas 1: Mata pelajaran dari penugasan guru mengambil kelas
        $subjectIdsFromAssignments = ClassSubjectTeacher::where('class_id', $student->class_id)
            ->pluck('subject_id')
            ->unique();

        // Prioritas 2 (fallback): Jika belum ada penugasan, ambil dari meeting/material
        if ($subjectIdsFromAssignments->isEmpty()) {
            $subjectIdsFromMeetings = Meeting::where('class_id', $student->class_id)->pluck('subject_id');
            $subjectIdsFromMaterials = Material::where('class_id', $student->class_id)->pluck('subject_id');
            $subjectIdsFromAssignments = $subjectIdsFromMeetings->merge($subjectIdsFromMaterials)->unique();
        }

        $subjects = Subject::whereIn('id', $subjectIdsFromAssignments)
            ->with('teacher.user')
            ->orderBy('name')
            ->get();

        return view('siswa.subjects.index', compact('subjects', 'student'));
    }

    /**
     * Menampilkan daftar Pertemuan untuk Mata Pelajaran tertentu
     */
    public function subjectMeetings(Subject $subject): View
    {
        $student = Student::where('user_id', Auth::id())->first();
        abort_unless($student, 403);

        $meetings = Meeting::where('class_id', $student->class_id)
            ->where('subject_id', $subject->id)
            ->with(['teacher.user'])
            ->orderBy('number', 'asc')
            ->get();

        // Juga ambil materi mandiri (tanpa pertemuan) untuk mapel ini
        $standaloneMaterials = Material::where('class_id', $student->class_id)
            ->where('subject_id', $subject->id)
            ->whereNull('meeting_id')
            ->get();

        return view('siswa.subjects.show', compact('subject', 'meetings', 'standaloneMaterials', 'student'));
    }

    /**
     * Menampilkan detail satu Pertemuan (Materi & Tugas)
     */
    public function meetingDetail(Meeting $meeting): View
    {
        $student = Student::where('user_id', Auth::id())->first();
        abort_unless($student, 403);
        
        // Pastikan pertemuan ini memang untuk kelas si siswa
        abort_if($meeting->class_id !== $student->class_id, 403);

        $meeting->load(['materials', 'assignments', 'subject', 'teacher.user']);

        return view('siswa.meetings.show', compact('meeting', 'student'));
    }
}
