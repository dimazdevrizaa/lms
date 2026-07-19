<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Kegiatan non-mapel pada slot jadwal, mis. "Upacara".
            // Diisi ketika slot bukan pelajaran biasa (subject_id & teacher_id null).
            $table->string('activity')->nullable()->after('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('activity');
        });
    }
};
