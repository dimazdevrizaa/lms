<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassSubjectTeacher;
use App\Models\ClassAttendance;
use App\Models\ClassAttendanceDetail;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\StudentGrade;
use App\Models\BehaviorRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoDataDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $class = SchoolClass::where('name', 'XI IPA 1')->first();
        if (!$class) {
            $this->command->error('XI IPA 1 class not found. Please seed DemoDataSeeder first.');
            return;
        }

        $students = Student::where('class_id', $class->id)->get();
        $andi = $students->where('nis', '21001')->first();

        if (!$andi) {
            $this->command->error('Andi Pratama not found.');
            return;
        }

        // Get subjects & teachers assigned to this class
        $classSubjects = ClassSubjectTeacher::where('class_id', $class->id)->get();

        // 1. Create Class Attendances (Daily Attendance - Wali Kelas)
        // 10 school days
        for ($i = 9; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            // Skip weekends
            if (Carbon::parse($date)->isWeekend()) {
                continue;
            }

            $classAttendance = ClassAttendance::updateOrCreate(
                ['class_id' => $class->id, 'date' => $date]
            );

            foreach ($students as $student) {
                // Determine status
                $status = 'hadir';
                $note = null;

                if ($student->id === $andi->id) {
                    if ($i === 5) {
                        $status = 'izin';
                        $note = 'Acara keluarga';
                    } elseif ($i === 2) {
                        $status = 'sakit';
                        $note = 'Demam';
                    }
                } else {
                    // Random small absenteeism for other students
                    if (rand(1, 20) === 1) {
                        $status = rand(1, 2) === 1 ? 'izin' : 'sakit';
                        $note = 'Izin sakit/berhalangan';
                    }
                }

                ClassAttendanceDetail::updateOrCreate(
                    ['class_attendance_id' => $classAttendance->id, 'student_id' => $student->id],
                    ['status' => $status, 'note' => $note]
                );
            }
        }

        // 2. Create Subject Attendances
        foreach ($classSubjects as $cs) {
            // 5 session dates
            for ($i = 4; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i * 2)->format('Y-m-d');
                if (Carbon::parse($date)->isWeekend()) {
                    continue;
                }

                $attendance = Attendance::updateOrCreate(
                    [
                        'class_id' => $class->id,
                        'subject_id' => $cs->subject_id,
                        'date' => $date
                    ],
                    [
                        'teacher_id' => $cs->teacher_id
                    ]
                );

                foreach ($students as $student) {
                    $status = 'hadir';
                    if ($student->id === $andi->id && $i === 2) {
                        $status = 'izin';
                    }

                    AttendanceDetail::updateOrCreate(
                        ['attendance_id' => $attendance->id, 'student_id' => $student->id],
                        ['status' => $status]
                    );
                }
            }
        }

        // 3. Create Assignments & Submissions
        foreach ($classSubjects as $cs) {
            $subject = Subject::find($cs->subject_id);

            // Assignment 1 (Graded)
            $assign1 = Assignment::updateOrCreate(
                [
                    'class_id' => $class->id,
                    'subject_id' => $cs->subject_id,
                    'title' => 'Tugas 1: Dasar ' . $subject->name
                ],
                [
                    'teacher_id' => $cs->teacher_id,
                    'description' => 'Kerjakan soal latihan bab 1 di buku cetak halaman 15-20.',
                    'due_at' => Carbon::now()->subDays(5)
                ]
            );

            // Submit for all students
            foreach ($students as $student) {
                // Score range: 75 to 95 for Andi, random for others
                $score = ($student->id === $andi->id) ? 88 : rand(70, 95);
                $feedback = ($student->id === $andi->id) ? 'Sangat bagus, pengerjaan rapi dan tepat.' : 'Kerja bagus.';

                AssignmentSubmission::updateOrCreate(
                    ['assignment_id' => $assign1->id, 'student_id' => $student->id],
                    [
                        'answer_text' => 'Berikut adalah jawaban saya untuk tugas 1.',
                        'submitted_at' => Carbon::now()->subDays(6),
                        'score' => $score,
                        'feedback' => $feedback
                    ]
                );
            }

            // Assignment 2 (Ungraded / Pending)
            $assign2 = Assignment::updateOrCreate(
                [
                    'class_id' => $class->id,
                    'subject_id' => $cs->subject_id,
                    'title' => 'Tugas 2: Pendalaman ' . $subject->name
                ],
                [
                    'teacher_id' => $cs->teacher_id,
                    'description' => 'Tuliskan rangkuman materi bab 2 dalam bentuk PDF.',
                    'due_at' => Carbon::now()->addDays(3)
                ]
            );

            // Submit for Andi and a few others, but no grade yet
            AssignmentSubmission::updateOrCreate(
                ['assignment_id' => $assign2->id, 'student_id' => $andi->id],
                [
                    'answer_text' => 'Saya mengumpulkan rangkuman bab 2.',
                    'submitted_at' => Carbon::now()->subDay(),
                    'score' => null,
                    'feedback' => null
                ]
            );
        }

        // 4. Create Student Grades (Academic Report Grades from Homeroom)
        foreach ($classSubjects as $cs) {
            // Ulangan Harian
            StudentGrade::updateOrCreate(
                [
                    'student_id' => $andi->id,
                    'class_id' => $class->id,
                    'subject_id' => $cs->subject_id,
                    'assessment_type' => 'Ulangan Harian 1',
                    'assessment_date' => Carbon::now()->subDays(10)->format('Y-m-d')
                ],
                ['score' => 85]
            );

            // UTS
            StudentGrade::updateOrCreate(
                [
                    'student_id' => $andi->id,
                    'class_id' => $class->id,
                    'subject_id' => $cs->subject_id,
                    'assessment_type' => 'UTS',
                    'assessment_date' => Carbon::now()->subDays(2)->format('Y-m-d')
                ],
                ['score' => 90]
            );
        }

        // 5. Create Behavior Records (Catatan Wali Kelas)
        BehaviorRecord::updateOrCreate(
            [
                'student_id' => $andi->id,
                'class_id' => $class->id,
                'title' => 'Aktif membantu teman sekelas'
            ],
            [
                'description' => 'Andi selalu aktif membantu menjelaskan materi pelajaran fisika kepada teman sekelasnya yang kesulitan.',
                'type' => 'positif',
                'date' => Carbon::now()->subDays(4)->format('Y-m-d')
            ]
        );

        BehaviorRecord::updateOrCreate(
            [
                'student_id' => $andi->id,
                'class_id' => $class->id,
                'title' => 'Sopan santun luar biasa'
            ],
            [
                'description' => 'Menunjukkan sikap hormat dan sopan yang konsisten kepada semua guru dan staf sekolah.',
                'type' => 'positif',
                'date' => Carbon::now()->subDays(8)->format('Y-m-d')
            ]
        );
    }
}
