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
        Schema::table('class_subject_teacher', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->cascadeOnDelete();
        });

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('class_subject_teacher', function (Blueprint $table) {
            $table->dropUnique('class_subject_teacher_class_id_subject_id_unique');
        });
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('class_subject_teacher', function (Blueprint $table) {
            $table->unique(['class_id', 'subject_id', 'academic_year_id'], 'cls_sub_ay_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_subject_teacher', function (Blueprint $table) {
            $table->dropUnique('cls_sub_ay_unique');
        });

        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('class_subject_teacher', function (Blueprint $table) {
            $table->unique(['class_id', 'subject_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
