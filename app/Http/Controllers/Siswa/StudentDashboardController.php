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
                'recentAssignments' => collect()
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

        return view('siswa.dashboard', compact(
            'submissionCount',
            'materialsCount',
            'attendanceRate',
            'averageGrade',
            'recentAssignments'
        ));
    }
}

