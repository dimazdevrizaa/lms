<?php

namespace App\Http\Controllers\TataUsaha;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function dashboard(): View
    {
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $classCount = SchoolClass::count();
        $academicYear = AcademicYear::where('is_active', true)->first();
        
        // Ambil statistik per kelas grouped by major
        $classes = SchoolClass::withCount('students')
            ->with('homeroomTeacher')
            ->get()
            ->groupBy('major');

        return view('tata-usaha.dashboard', compact(
            'studentCount', 
            'teacherCount', 
            'classCount', 
            'academicYear',
            'classes'
        ));
    }

    public function index(): View
    {
        $students = Student::with('schoolClass')->orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();

        return view('tata-usaha.reports.index', compact('students', 'teachers'));
    }

    public function print(): View
    {
        $students = Student::with('schoolClass')->orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();

        return view('tata-usaha.reports.print', compact('students', 'teachers'));
    }
}

