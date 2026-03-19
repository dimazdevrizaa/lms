<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $teacherCount = User::where('role', 'guru')->count();
        $studentCount = User::where('role', 'siswa')->count();
        $classCount = \App\Models\SchoolClass::count();

        return view('admin.dashboard', compact(
            'userCount',
            'teacherCount',
            'studentCount',
            'classCount',
        ));
    }
}

