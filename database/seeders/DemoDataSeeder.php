<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassSubjectTeacher;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tahun Ajaran
        $year = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );

        // 2. Mata Pelajaran & Guru (major: IPA, IPS, Umum)
        $subjectsData = [
            ['name' => 'Fisika', 'code' => 'FIS', 'major' => 'IPA', 'teacher_name' => 'Dr. Ahmad Subarjo', 'email' => 'ahmad@sma15padang.test', 'nip' => '197501012000031001'],
            ['name' => 'Biologi', 'code' => 'BIO', 'major' => 'IPA', 'teacher_name' => 'Sari Wahyuni, M.Pd', 'email' => 'sari@sma15padang.test', 'nip' => '198205122008012002'],
            ['name' => 'Bahasa Inggris', 'code' => 'ING', 'major' => 'Umum', 'teacher_name' => 'John Doe, M.A', 'email' => 'john@sma15padang.test', 'nip' => '198809202015031003'],
            ['name' => 'Sejarah', 'code' => 'SEJ', 'major' => 'IPS', 'teacher_name' => 'Drs. Bambang Heru', 'email' => 'bambang@sma15padang.test', 'nip' => '197012011995121004'],
        ];

        $teachers = [];
        $subjects = [];
        foreach ($subjectsData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['teacher_name'], 'role' => 'guru', 'password' => Hash::make('password')]
            );

            $teacher = Teacher::updateOrCreate(
                ['user_id' => $user->id],
                ['nip' => $data['nip'], 'phone' => '0812' . rand(10000000, 99999999)]
            );

            $subject = Subject::updateOrCreate(
                ['code' => $data['code']],
                ['name' => $data['name'], 'major' => $data['major'], 'teacher_id' => $teacher->id]
            );

            $teachers[$data['code']] = $teacher;
            $subjects[$data['code']] = $subject;
        }

        // 3. Kelas (Beberapa guru jadi Wali Kelas) - tambah major
        $classesData = [
            ['name' => 'XI IPA 1', 'level' => 'XI', 'major' => 'IPA', 'homeroom' => 'FIS'],
            ['name' => 'XI IPA 2', 'level' => 'XI', 'major' => 'IPA', 'homeroom' => 'BIO'],
            ['name' => 'X IPS 1', 'level' => 'X', 'major' => 'IPS', 'homeroom' => 'SEJ'],
        ];

        $classes = [];
        foreach ($classesData as $cData) {
            $class = SchoolClass::updateOrCreate(
                ['name' => $cData['name']],
                [
                    'level' => $cData['level'],
                    'major' => $cData['major'],
                    'academic_year_id' => $year->id,
                    'homeroom_teacher_id' => $teachers[$cData['homeroom']]->id
                ]
            );
            $classes[$cData['name']] = $class;
        }

        // 4. Penugasan Guru Mengambil Kelas (guru mengambil kelas - siswa punya mapel)
        // IPA: Fisika, Biologi -> XI IPA 1, XI IPA 2
        // IPS: Sejarah -> X IPS 1
        // Umum: Bahasa Inggris -> semua kelas
        foreach ($subjects as $code => $subject) {
            if (!$subject->teacher_id) continue;

            foreach ($classes as $class) {
                $classMajor = strtoupper($class->major ?? '');
                $subjectMajor = strtoupper($subject->major ?? 'UMUM');

                $match = match ($subjectMajor) {
                    'IPA' => $classMajor === 'IPA',
                    'IPS' => $classMajor === 'IPS',
                    'UMUM' => true,
                    default => $classMajor === $subjectMajor,
                };

                if (!$match) continue;

                ClassSubjectTeacher::firstOrCreate(
                    ['class_id' => $class->id, 'subject_id' => $subject->id],
                    ['teacher_id' => $subject->teacher_id]
                );
            }
        }

        // 5. Siswa
        $studentsData = [
            ['name' => 'Andi Pratama', 'email' => 'andi@sma15padang.test', 'nis' => '21001', 'class' => 'XI IPA 1'],
            ['name' => 'Budi Setiawan', 'email' => 'budi.s@sma15padang.test', 'nis' => '21002', 'class' => 'XI IPA 1'],
            ['name' => 'Cika Aurelia', 'email' => 'cika@sma15padang.test', 'nis' => '21003', 'class' => 'XI IPA 2'],
            ['name' => 'Deni Ramadhan', 'email' => 'deni@sma15padang.test', 'nis' => '21004', 'class' => 'XI IPA 2'],
            ['name' => 'Eka Putri', 'email' => 'eka@sma15padang.test', 'nis' => '22001', 'class' => 'X IPS 1'],
            ['name' => 'Fahri Hamzah', 'email' => 'fahri@sma15padang.test', 'nis' => '22002', 'class' => 'X IPS 1'],
        ];

        foreach ($studentsData as $sData) {
            $user = User::updateOrCreate(
                ['email' => $sData['email']],
                ['name' => $sData['name'], 'role' => 'siswa', 'password' => Hash::make('password')]
            );

            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $sData['nis'],
                    'phone' => '0878' . rand(10000000, 99999999),
                    'class_id' => $classes[$sData['class']]->id
                ]
            );
        }
    }
}
