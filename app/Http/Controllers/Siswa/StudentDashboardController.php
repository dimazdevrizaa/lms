<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use App\Models\Material;
use App\Models\ClassAttendanceDetail;
use App\Models\StudentGrade;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return view('siswa.dashboard', [
                'submissionCount' => 0,
                'materialsCount' => 0,
                'attendanceRate' => 0,
                'averageGrade' => 0,
                'recentAssignments' => collect(),
                'todaySchedules' => collect(),
                'todayDayName' => 'Senin'
            ]);
        }

        $submissionCount = AssignmentSubmission::where('student_id', $student->id)->count();
        
        // Materi untuk kelas siswa ini
        $materialsCount = Material::where('class_id', $student->class_id)->count();

        // Kehadiran
        $totalAttendance = ClassAttendanceDetail::where('student_id', $student->id)->count();
        $presentCount = ClassAttendanceDetail::where('student_id', $student->id)
            ->where('status', 'hadir')->count();
        $attendanceRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;

        // Nilai Rata-rata
        $averageGrade = StudentGrade::where('student_id', $student->id)->avg('score');
        $averageGrade = $averageGrade ? round($averageGrade, 1) : 0;

        // Tugas Terbaru
        $recentAssignments = Assignment::where('class_id', $student->class_id)
            ->with(['subject', 'teacher'])
            ->latest()
            ->take(5)
            ->get();

        // ponytail: map English day names to Indonesian
        $daysMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $todayDayName = $daysMap[date('l')] ?? 'Senin';

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();

        $todaySchedules = collect();
        if ($activeYear) {
            $rawSchedules = \App\Models\Schedule::where('class_id', $student->class_id)
                ->where('academic_year_id', $activeYear->id)
                ->where('day', $todayDayName)
                ->with(['timeSlot', 'subject', 'teacher.user'])
                ->get();

            $todaySchedules = \App\Models\Schedule::groupConsecutiveSchedules($rawSchedules);
        }

        return view('siswa.dashboard', compact(
            'submissionCount',
            'materialsCount',
            'attendanceRate',
            'averageGrade',
            'recentAssignments',
            'todaySchedules',
            'todayDayName'
        ));
    }

    public function directory(): View
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->with('schoolClass.homeroomTeacher.user')->first();

        if (!$student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $schoolClass = $student->schoolClass;

        // Teman Sekelas (Sorted Alphabetically A-Z)
        $classmates = Student::where('class_id', $student->class_id)
            ->where('id', '!=', $student->id)
            ->with('user')
            ->get()
            ->sortBy(fn($s) => strtolower($s->user?->name ?? ''), SORT_NATURAL)
            ->values();

        // Wali Kelas
        $homeroomTeacher = $schoolClass->homeroomTeacher;

        // Guru Mapel (Sorted Alphabetically A-Z by Teacher Name)
        $subjectTeachers = \App\Models\ClassSubjectTeacher::where('class_id', $student->class_id)
            ->with(['teacher.user', 'subject'])
            ->get()
            ->sortBy(fn($t) => strtolower($t->teacher->user?->name ?? ''), SORT_NATURAL)
            ->values();

        return view('siswa.directory', compact('schoolClass', 'classmates', 'homeroomTeacher', 'subjectTeachers'));
    }
}

