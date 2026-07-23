<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendance_details MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'alpa', 'cabut') NOT NULL DEFAULT 'hadir'");
            if (Schema::hasTable('homeroom_attendance_details')) {
                DB::statement("ALTER TABLE homeroom_attendance_details MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'alpa', 'cabut') NOT NULL DEFAULT 'hadir'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendance_details MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'alpa') NOT NULL DEFAULT 'hadir'");
            if (Schema::hasTable('homeroom_attendance_details')) {
                DB::statement("ALTER TABLE homeroom_attendance_details MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'alpa') NOT NULL DEFAULT 'hadir'");
            }
        }
    }
};
