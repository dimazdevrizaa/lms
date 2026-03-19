<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop tables if they exist (untuk rollback automation)
        Schema::dropIfExists('class_attendance_details');
        Schema::dropIfExists('class_attendances');
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('behavior_records');

        // Catatan Perilaku Siswa
        Schema::create('behavior_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('title'); // Judul catatan
            $table->text('description'); // Deskripsi perilaku
            $table->enum('type', ['positif', 'negatif'])->default('positif'); // Jenis catatan
            $table->date('date'); // Tanggal kejadian
            $table->timestamps();
        });

        // Nilai Siswa
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('assessment_type'); // Ulangan, UTS, UAS, Tugas, dll
            $table->unsignedInteger('score'); // Nilai 0-100
            $table->date('assessment_date'); // Tanggal penilaian
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'assessment_type', 'assessment_date'], 'unique_student_grade');
        });

        // Class Attendance (Absensi Harian Kelas - bukan per mapel)
        Schema::create('class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->date('date');
            $table->timestamps();

            $table->unique(['class_id', 'date']);
        });

        // Class Attendance Detail
        Schema::create('class_attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_attendance_id')->constrained('class_attendances')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['class_attendance_id', 'student_id'], 'unique_class_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('class_attendance_details');
        Schema::dropIfExists('class_attendances');
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('behavior_records');

        Schema::enableForeignKeyConstraints();
    }
};
