<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Guru\AssignmentController;
use App\Http\Controllers\Guru\AttendanceController;
use App\Http\Controllers\Guru\ClassroomController;
use App\Http\Controllers\Guru\MaterialController;
use App\Http\Controllers\Guru\MeetingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\StudentAttendanceController;
use App\Http\Controllers\Siswa\StudentDashboardController;
use App\Http\Controllers\Siswa\StudentMaterialController;
use App\Http\Controllers\Siswa\StudentSubmissionController;
use App\Http\Controllers\TataUsaha\ReportController;
use App\Http\Controllers\TataUsaha\StudentController;
use App\Http\Controllers\TataUsaha\TeacherController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\TataUsaha\TeachingAssignmentController;
use App\Http\Controllers\TataUsaha\ScheduleController;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'tatausaha' => redirect()->route('tatausaha.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        default => redirect()->route('siswa.dashboard'),
    };
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'tatausaha' => redirect()->route('tatausaha.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
        default => redirect()->route('siswa.dashboard'),
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('academic-years', AcademicYearController::class)->except(['show']);
        Route::resource('classes', AdminClassController::class)->except(['show']);
        Route::resource('subjects', SubjectController::class);
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    });

    // TATA USAHA
    Route::middleware('role:tatausaha')->prefix('tata-usaha')->name('tatausaha.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);

        Route::get('teaching-assignments/print', [TeachingAssignmentController::class, 'printPdf'])->name('teaching-assignments.print');
        Route::resource('teaching-assignments', TeachingAssignmentController::class)->except(['show'])->parameters([
            'teaching-assignments' => 'teaching_assignment',
        ]);
        // Jadwal Pelajaran
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [ScheduleController::class, 'index'])->name('index');
            Route::get('/time-slots', [ScheduleController::class, 'timeSlots'])->name('time-slots');
            Route::post('/time-slots', [ScheduleController::class, 'storeTimeSlot'])->name('time-slots.store');
            Route::delete('/time-slots/{timeSlot}', [ScheduleController::class, 'deleteTimeSlot'])->name('time-slots.destroy');
            Route::get('/{schoolClass}/edit', [ScheduleController::class, 'edit'])->name('edit');
            Route::put('/{schoolClass}', [ScheduleController::class, 'update'])->name('update');
            Route::get('/print', [ScheduleController::class, 'printSchedule'])->name('print');
        });

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/print', [ReportController::class, 'print'])->name('reports.print');
    });

    // GURU
    Route::middleware('role:guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [MaterialController::class, 'dashboard'])->name('dashboard');
        Route::resource('materials', MaterialController::class);
        Route::resource('meetings', MeetingController::class);
        Route::resource('assignments', AssignmentController::class);
        Route::resource('attendances', AttendanceController::class);

        // WALI KELAS - Classroom Management
        Route::prefix('classroom')->name('classroom.')->group(function () {
            Route::get('/', [ClassroomController::class, 'index'])->name('index');
            Route::get('/{class}', [ClassroomController::class, 'show'])->name('show');

            // Absensi Harian Kelas
            Route::get('/{class}/attendance', [ClassroomController::class, 'attendance'])->name('attendance');
            Route::get('/{class}/attendance/create', [ClassroomController::class, 'attendanceCreate'])->name('attendance.create');
            Route::post('/{class}/attendance', [ClassroomController::class, 'attendanceStore'])->name('attendance.store');
            Route::get('/{class}/attendance/{attendance}', [ClassroomController::class, 'attendanceShow'])->name('attendance.show');

            // Catatan Perilaku
            Route::get('/{class}/behavior', [ClassroomController::class, 'behavior'])->name('behavior');
            Route::get('/{class}/behavior/create', [ClassroomController::class, 'behaviorCreate'])->name('behavior.create');
            Route::post('/{class}/behavior', [ClassroomController::class, 'behaviorStore'])->name('behavior.store');
            Route::delete('/{class}/behavior/{behavior}', [ClassroomController::class, 'behaviorDestroy'])->name('behavior.destroy');

            // Rekap Nilai
            Route::get('/{class}/grades', [ClassroomController::class, 'grades'])->name('grades');
            Route::get('/{class}/grades/input', [ClassroomController::class, 'gradesInput'])->name('grades.input');
            Route::post('/{class}/grades', [ClassroomController::class, 'gradesStore'])->name('grades.store');
        });
    });

    // SISWA
    Route::middleware('role:siswa')->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        
        // Flow Baru: Mapel -> Pertemuan -> Detail
        Route::get('/subjects', [StudentMaterialController::class, 'subjects'])->name('subjects.index');
        Route::get('/subjects/{subject}', [StudentMaterialController::class, 'subjectMeetings'])->name('subjects.show');
        Route::get('/meetings/{meeting}', [StudentMaterialController::class, 'meetingDetail'])->name('meetings.show');
        
        Route::get('/assignments', [StudentSubmissionController::class, 'index'])->name('assignments.index');
        Route::post('/assignments/{assignment}/submit', [StudentSubmissionController::class, 'store'])->name('assignments.submit');
        Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
    });
});

require __DIR__.'/auth.php';
