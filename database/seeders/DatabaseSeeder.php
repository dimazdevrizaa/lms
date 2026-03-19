<?php

namespace Database\Seeders;

use App\Models\AdminStaff;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Account
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@sma15padang.test'],
            ['name' => 'Admin Sekolah', 'role' => 'admin', 'password' => Hash::make('password')],
        );

        AdminStaff::updateOrCreate(
            ['user_id' => $adminUser->id],
            ['nip' => '198501011990031001', 'phone' => '081234567890', 'position' => 'admin'],
        );

        // 2. Tata Usaha Account
        $tuUser = User::updateOrCreate(
            ['email' => 'tu@sma15padang.test'],
            ['name' => 'Tata Usaha', 'role' => 'tatausaha', 'password' => Hash::make('password')],
        );

        AdminStaff::updateOrCreate(
            ['user_id' => $tuUser->id],
            ['nip' => '198601011995032002', 'phone' => '081234567891', 'position' => 'tatausaha'],
        );

        // 3. Guru Account
        $guruUser = User::updateOrCreate(
            ['email' => 'guru@sma15padang.test'],
            ['name' => 'Budi Santoso', 'role' => 'guru', 'password' => Hash::make('password')],
        );

        $teacher = Teacher::updateOrCreate(
            ['user_id' => $guruUser->id],
            ['nip' => '198001012006011001', 'phone' => '081234567892'],
        );

        $year = AcademicYear::updateOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true],
        );

        $class = SchoolClass::updateOrCreate(
            ['name' => 'X IPA 1'],
            ['level' => 'X', 'academic_year_id' => $year->id, 'homeroom_teacher_id' => $teacher->id],
        );

        $subject = Subject::updateOrCreate(
            ['name' => 'Matematika'],
            ['code' => 'MTK', 'teacher_id' => $teacher->id],
        );

        // 4. Siswa Account
        $siswaUser = User::updateOrCreate(
            ['email' => 'siswa@sma15padang.test'],
            ['name' => 'Siti Aminah', 'role' => 'siswa', 'password' => Hash::make('password')],
        );

        Student::updateOrCreate(
            ['user_id' => $siswaUser->id],
            ['nis' => '1234567890', 'phone' => '081234567892', 'class_id' => 1],
        );
    }
}
