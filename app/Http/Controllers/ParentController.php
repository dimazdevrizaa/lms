<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassAttendanceDetail;
use App\Models\AttendanceDetail;
use App\Models\StudentGrade;
use App\Models\AssignmentSubmission;
use App\Models\BehaviorRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ParentController extends Controller
{
    /**
     * Durasi timeout sesi orang tua (dalam menit).
     */
    private const SESSION_TIMEOUT_MINUTES = 30;

    public function index()
    {
        // If already logged in as parent, redirect to dashboard
        if (session()->has('parent_student_id')) {
            // Check session expiry
            if ($this->isSessionExpired()) {
                $this->clearParentSession();
                return view('parent.index')->withErrors(['parent_code' => 'Sesi Anda telah berakhir. Silakan masukkan kode akses kembali.']);
            }
            return redirect()->route('parent.dashboard');
        }
        return view('parent.index');
    }

    public function access(Request $request)
    {
        $request->validate([
            'parent_code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->parent_code));

        $student = Student::where('parent_code', $code)->first();

        if (!$student) {
            // Log failed access attempt for security monitoring
            Log::warning('Parent portal: failed access attempt', [
                'code' => substr($code, 0, 6) . '****',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors(['parent_code' => 'Kode akses orang tua tidak valid atau tidak ditemukan.']);
        }

        // Log successful access
        Log::info('Parent portal: successful access', [
            'student_id' => $student->id,
            'ip' => $request->ip(),
        ]);

        session([
            'parent_student_id' => $student->id,
            'parent_last_activity' => now()->timestamp,
        ]);

        return redirect()->route('parent.dashboard')->with('success', 'Berhasil masuk ke dashboard pemantauan orang tua.');
    }

    public function viewDirect($code)
    {
        $code = strtoupper(trim($code));
        $student = Student::where('parent_code', $code)->first();

        if (!$student) {
            Log::warning('Parent portal: invalid direct link access', [
                'code' => substr($code, 0, 6) . '****',
                'ip' => request()->ip(),
            ]);

            return redirect()->route('parent.index')->withErrors(['parent_code' => 'Link akses tidak valid.']);
        }

        session([
            'parent_student_id' => $student->id,
            'parent_last_activity' => now()->timestamp,
        ]);

        return redirect()->route('parent.dashboard');
    }

    public function dashboard()
    {
        $studentId = session('parent_student_id');
        
        if (!$studentId) {
            return redirect()->route('parent.index')->withErrors(['parent_code' => 'Silakan masukkan kode akses terlebih dahulu.']);
        }

        // Check session expiry
        if ($this->isSessionExpired()) {
            $this->clearParentSession();
            return redirect()->route('parent.index')->withErrors(['parent_code' => 'Sesi Anda telah berakhir (tidak aktif selama ' . self::SESSION_TIMEOUT_MINUTES . ' menit). Silakan masukkan kode akses kembali.']);
        }

        // Update last activity timestamp
        session(['parent_last_activity' => now()->timestamp]);

        $student = Student::with(['user', 'schoolClass.academicYear'])->findOrFail($studentId);

        // Daily Attendance (Wali Kelas)
        $dailyAttendances = ClassAttendanceDetail::where('student_id', $studentId)
            ->join('class_attendances', 'class_attendance_details.class_attendance_id', '=', 'class_attendances.id')
            ->select('class_attendance_details.*')
            ->orderBy('class_attendances.date', 'desc')
            ->with('attendance')
            ->get();

        // Subject Attendance
        $subjectAttendances = AttendanceDetail::where('student_id', $studentId)
            ->join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->select('attendance_details.*')
            ->orderBy('attendances.date', 'desc')
            ->with(['attendance.subject', 'attendance.teacher.user'])
            ->get();

        // Grades (Rapor / Input Nilai)
        $grades = StudentGrade::where('student_id', $studentId)
            ->with(['subject', 'class'])
            ->latest()
            ->get();

        // Assignment Submissions (Tugas & Latihan)
        $submissions = AssignmentSubmission::where('student_id', $studentId)
            ->with('assignment.subject')
            ->latest()
            ->get();

        // Behavior Records
        $behaviorRecords = BehaviorRecord::where('student_id', $studentId)
            ->latest()
            ->get();

        return view('parent.dashboard', compact(
            'student',
            'dailyAttendances',
            'subjectAttendances',
            'grades',
            'submissions',
            'behaviorRecords'
        ));
    }

    public function logout()
    {
        $this->clearParentSession();
        return redirect()->route('parent.index')->with('success', 'Berhasil keluar dari dashboard pemantauan.');
    }

    /**
     * Check if parent session has expired due to inactivity.
     */
    private function isSessionExpired(): bool
    {
        $lastActivity = session('parent_last_activity');
        if (!$lastActivity) {
            return true;
        }

        $timeoutSeconds = self::SESSION_TIMEOUT_MINUTES * 60;
        return (now()->timestamp - $lastActivity) > $timeoutSeconds;
    }

    /**
     * Clear all parent-related session data.
     */
    private function clearParentSession(): void
    {
        session()->forget(['parent_student_id', 'parent_last_activity']);
    }
}
