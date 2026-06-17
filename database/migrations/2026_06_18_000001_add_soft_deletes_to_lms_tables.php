<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan soft delete pada tabel-tabel penting LMS
     * agar data akademik tidak hilang permanen saat dihapus.
     */
    public function up(): void
    {
        $tables = [
            'users',
            'teachers',
            'students',
            'assignments',
            'assignment_submissions',
            'materials',
            'meetings',
            'attendances',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'teachers',
            'students',
            'assignments',
            'assignment_submissions',
            'materials',
            'meetings',
            'attendances',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropSoftDeletes();
                });
            }
        }
    }
};
