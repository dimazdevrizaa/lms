<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\TimeSlot;
use App\Models\Schedule;
use App\Models\ClassSubjectTeacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KelasDigitalScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get or create Kelas Digital
        $class = SchoolClass::firstOrCreate(
            ['name' => 'Kelas Digital'],
            [
                'level' => 'X',
                'academic_year_id' => AcademicYear::where('is_active', true)->first()?->id,
            ]
        );

        // 2. Get active academic year
        $year = AcademicYear::where('is_active', true)->first();
        if (!$year) {
            $year = AcademicYear::first();
        }
        if (!$year) {
            return;
        }

        // 3. Define subjects & teachers
        $subjectsData = [
            ['name' => 'Matematika', 'code' => 'MTK', 'teacher_name' => 'Budi Santoso', 'email' => 'guru@sma15padang.test'],
            ['name' => 'Fisika', 'code' => 'FIS', 'teacher_name' => 'Dr. Ahmad Subarjo', 'email' => 'ahmad@sma15padang.test'],
            ['name' => 'Biologi', 'code' => 'BIO', 'teacher_name' => 'Sari Wahyuni, M.Pd', 'email' => 'sari@sma15padang.test'],
            ['name' => 'Bahasa Inggris', 'code' => 'ING', 'teacher_name' => 'John Doe, M.A', 'email' => 'john@sma15padang.test'],
            ['name' => 'Sejarah', 'code' => 'SEJ', 'teacher_name' => 'Bambang Heru', 'email' => 'bambang@sma15padang.test'],
            ['name' => 'Kimia', 'code' => 'KIM', 'teacher_name' => 'Dra. Herlina', 'email' => 'herlina@sma15padang.test'],
            ['name' => 'Bahasa Indonesia', 'code' => 'IND', 'teacher_name' => 'Siti Nurhaliza, S.Pd', 'email' => 'siti.n@sma15padang.test'],
            ['name' => 'Geografi', 'code' => 'GEO', 'teacher_name' => 'Drs. Mulyadi', 'email' => 'mulyadi@sma15padang.test'],
            ['name' => 'Sosiologi', 'code' => 'SOS', 'teacher_name' => 'Rina Wijaya, S.Sos', 'email' => 'rina@sma15padang.test'],
            ['name' => 'Ekonomi', 'code' => 'EKO', 'teacher_name' => 'Joko Susilo, M.E', 'email' => 'joko@sma15padang.test'],
            ['name' => 'PJOK', 'code' => 'PJK', 'teacher_name' => 'Aris Munandar, S.Pd', 'email' => 'aris@sma15padang.test'],
            ['name' => 'PKn', 'code' => 'PKN', 'teacher_name' => 'Dra. Endang Lestari', 'email' => 'endang@sma15padang.test'],
            ['name' => 'Seni Budaya', 'code' => 'SEN', 'teacher_name' => 'Yudi Prasetyo, S.Sn', 'email' => 'yudi@sma15padang.test'],
            ['name' => 'Pendidikan Agama', 'code' => 'AGM', 'teacher_name' => 'H. M. Syukri, S.Ag', 'email' => 'syukri@sma15padang.test'],
            ['name' => 'Informatika', 'code' => 'INF', 'teacher_name' => 'Rian Hidayat, S.Kom', 'email' => 'rian@sma15padang.test'],
        ];

        $subjects = [];
        $teachers = [];

        foreach ($subjectsData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['teacher_name'], 'role' => 'guru', 'password' => Hash::make('password')]
            );

            // ponytail: generate clean fake NIP/phone if not exists
            $teacher = Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $user->teacher->nip ?? ('1980' . rand(100000, 999999) . '200' . rand(0, 9) . rand(0, 9) . rand(1, 2) . rand(0, 9) . rand(0, 9) . rand(1, 9)),
                    'phone' => $user->teacher->phone ?? ('0812' . rand(10000000, 99999999))
                ]
            );

            $subject = Subject::updateOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name']]
            );

            ClassSubjectTeacher::updateOrCreate(
                [
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $year->id,
                ],
                [
                    'teacher_id' => $teacher->id,
                ]
            );

            $subjects[] = $subject;
            $teachers[$subject->id] = $teacher;
        }

        // 4. Get Time Slots
        $timeSlots = TimeSlot::where('academic_year_id', $year->id)
            ->where('type', 'lesson')
            ->orderBy('slot_order')
            ->get();

        if ($timeSlots->isEmpty()) {
            $defaults = [
                ['slot_order' => 1,  'type' => 'lesson', 'label' => 'Jam ke-1',    'start_time' => '07:30', 'end_time' => '08:15'],
                ['slot_order' => 2,  'type' => 'lesson', 'label' => 'Jam ke-2',    'start_time' => '08:15', 'end_time' => '09:00'],
                ['slot_order' => 3,  'type' => 'lesson', 'label' => 'Jam ke-3',    'start_time' => '09:00', 'end_time' => '09:45'],
                ['slot_order' => 4,  'type' => 'break',  'label' => 'Istirahat 1', 'start_time' => '09:45', 'end_time' => '10:00'],
                ['slot_order' => 5,  'type' => 'lesson', 'label' => 'Jam ke-4',    'start_time' => '10:00', 'end_time' => '10:45'],
                ['slot_order' => 6,  'type' => 'lesson', 'label' => 'Jam ke-5',    'start_time' => '10:45', 'end_time' => '11:30'],
                ['slot_order' => 7,  'type' => 'lesson', 'label' => 'Jam ke-6',    'start_time' => '11:30', 'end_time' => '12:15'],
                ['slot_order' => 8,  'type' => 'break',  'label' => 'Istirahat 2', 'start_time' => '12:15', 'end_time' => '12:45'],
                ['slot_order' => 9,  'type' => 'lesson', 'label' => 'Jam ke-7',    'start_time' => '12:45', 'end_time' => '13:30'],
                ['slot_order' => 10, 'type' => 'lesson', 'label' => 'Jam ke-8',    'start_time' => '13:30', 'end_time' => '14:15'],
                ['slot_order' => 11, 'type' => 'lesson', 'label' => 'Jam ke-9',    'start_time' => '14:15', 'end_time' => '15:00'],
                ['slot_order' => 12, 'type' => 'lesson', 'label' => 'Jam ke-10',   'start_time' => '15:00', 'end_time' => '15:45'],
            ];

            foreach ($defaults as $slot) {
                TimeSlot::create(array_merge($slot, ['academic_year_id' => $year->id]));
            }

            $timeSlots = TimeSlot::where('academic_year_id', $year->id)
                ->where('type', 'lesson')
                ->orderBy('slot_order')
                ->get();
        }

        $days = Schedule::DAYS;
        $subjectCount = count($subjects);
        $slotIndex = 0;

        // Delete existing schedules for this class and academic year to prevent conflicts
        Schedule::where('class_id', $class->id)
            ->where('academic_year_id', $year->id)
            ->delete();

        foreach ($days as $dayIndex => $day) {
            foreach ($timeSlots as $tsIndex => $timeSlot) {
                $subject = $subjects[$slotIndex % $subjectCount];
                $teacher = $teachers[$subject->id];

                Schedule::create([
                    'academic_year_id' => $year->id,
                    'class_id' => $class->id,
                    'day' => $day,
                    'time_slot_id' => $timeSlot->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $teacher->id,
                ]);

                $slotIndex++;
            }
        }
    }
}
