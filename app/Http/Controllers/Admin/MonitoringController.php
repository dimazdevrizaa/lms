<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Material;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::count(),
            'academic_years' => AcademicYear::count(),
            'classes' => SchoolClass::count(),
            'teachers' => Teacher::count(),
            'students' => Student::count(),
            'materials' => Material::count(),
            'assignments' => Assignment::count(),
            'attendances' => Attendance::count(),
        ];

        return view('admin.monitoring.index', compact('stats'));
    }
}

