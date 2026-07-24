<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Meeting;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Step 1: List all classes for Admin attendance management.
     */
    public function index(): View
    {
        $classes = SchoolClass::withCount(['students', 'meetings'])
            ->orderBy('name')
            ->get();

        return view('admin.attendance.index', compact('classes'));
    }

    /**
     * Step 2: List all subjects for a selected class.
     */
    public function showClass(SchoolClass $class): View
    {
        $class->loadCount('students');

        // Retrieve subjects linked to this class via schedules, teaching assignments, or meetings
        $subjectIdsFromSchedules = DB::table('schedules')->where('class_id', $class->id)->pluck('subject_id');
        $subjectIdsFromMeetings = DB::table('meetings')->where('class_id', $class->id)->pluck('subject_id');
        $subjectIdsFromAssignments = DB::table('class_subject_teacher')->where('class_id', $class->id)->pluck('subject_id');

        $allSubjectIds = $subjectIdsFromSchedules
            ->concat($subjectIdsFromMeetings)
            ->concat($subjectIdsFromAssignments)
            ->unique()
            ->filter();

        $subjects = Subject::whereIn('id', $allSubjectIds)
            ->orderBy('name')
            ->get();

        if ($subjects->isEmpty()) {
            $subjects = Subject::orderBy('name')->get();
        }

        foreach ($subjects as $subject) {
            $subject->meeting_count = Meeting::where('class_id', $class->id)
                ->where('subject_id', $subject->id)
                ->count();

            $subject->completed_attendance_count = Meeting::where('class_id', $class->id)
                ->where('subject_id', $subject->id)
                ->whereHas('attendance')
                ->count();
        }

        return view('admin.attendance.subjects', compact('class', 'subjects'));
    }

    /**
     * Step 3: List all meetings for a selected class & subject.
     */
    public function showSubject(SchoolClass $class, Subject $subject): View
    {
        $class->loadCount('students');

        $meetings = Meeting::where('class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->with(['teacher.user', 'attendance.details', 'materials', 'assignments'])
            ->orderBy('date', 'desc')
            ->orderBy('number', 'desc')
            ->get();

        return view('admin.attendance.meetings', compact('class', 'subject', 'meetings'));
    }

    /**
     * Step 4: Form for Admin to edit/change attendance of a specific meeting.
     */
    public function editMeetingAttendance(Meeting $meeting): View
    {
        $meeting->load(['schoolClass.students.user', 'subject', 'teacher.user', 'attendance.details']);

        $class = $meeting->schoolClass;
        $subject = $meeting->subject;

        // Sort students alphabetically A-Z
        $students = $class->students->sortBy(fn($s) => strtolower($s->user?->name ?? ''), SORT_NATURAL)->values();

        // Map existing status for each student
        $existingStatus = [];
        if ($meeting->attendance) {
            foreach ($meeting->attendance->details as $detail) {
                $existingStatus[$detail->student_id] = $detail->status;
            }
        }

        // Gather student historical attendance stats for this subject
        $studentIds = $students->pluck('id');
        $rawStats = AttendanceDetail::whereIn('student_id', $studentIds)
            ->whereHas('attendance', function ($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })
            ->select('student_id', 'status', DB::raw('count(*) as count'))
            ->groupBy('student_id', 'status')
            ->get();

        $studentStats = [];
        foreach ($rawStats as $stat) {
            $studentStats[$stat->student_id][$stat->status] = $stat->count;
        }

        return view('admin.attendance.edit', compact('meeting', 'class', 'subject', 'students', 'existingStatus', 'studentStats'));
    }

    /**
     * Save/Update attendance details for a meeting (Admin Override).
     */
    public function updateMeetingAttendance(Request $request, Meeting $meeting): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'statuses' => ['required', 'array'],
            'statuses.*' => ['required', 'in:hadir,izin,sakit,alpa,cabut'],
        ]);

        $attendance = Attendance::firstOrCreate(
            ['meeting_id' => $meeting->id],
            [
                'class_id' => $meeting->class_id,
                'subject_id' => $meeting->subject_id,
                'teacher_id' => $meeting->teacher_id,
                'date' => $data['date'],
                'submitted_at' => now(),
            ]
        );

        $attendance->update([
            'date' => $data['date'],
            'submitted_at' => now(),
        ]);

        $statuses = $data['statuses'];
        $students = Student::where('class_id', $meeting->class_id)->pluck('id');

        foreach ($students as $studentId) {
            $status = $statuses[$studentId] ?? 'hadir';

            AttendanceDetail::updateOrCreate(
                [
                    'attendance_id' => $attendance->id,
                    'student_id' => (int) $studentId,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('admin.attendances.showSubject', [
            'class' => $meeting->class_id,
            'subject' => $meeting->subject_id,
        ])->with('success', "Presensi Pertemuan #{$meeting->number} ({$meeting->subject->name}) berhasil disimpan/diperbarui oleh Admin.");
    }
}
