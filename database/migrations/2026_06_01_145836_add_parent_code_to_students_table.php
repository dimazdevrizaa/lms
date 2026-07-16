<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('students', 'parent_code')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('parent_code')->nullable()->unique()->after('class_id');
            });
        }

        // Generate codes for existing students
        DB::table('students')->whereNull('parent_code')->orderBy('id')->chunkById(100, function ($students) {
            foreach ($students as $student) {
                do {
                    $code = strtoupper(Str::random(6));
                } while (DB::table('students')->where('parent_code', $code)->exists());

                DB::table('students')
                    ->where('id', $student->id)
                    ->update(['parent_code' => $code]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('parent_code');
        });
    }
};
