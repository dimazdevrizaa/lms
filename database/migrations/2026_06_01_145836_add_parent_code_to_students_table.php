<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('parent_code')->nullable()->unique()->after('class_id');
        });

        // Generate codes for existing students
        Student::whereNull('parent_code')->chunkById(100, function ($students) {
            foreach ($students as $student) {
                do {
                    $code = 'ORTU-' . strtoupper(Str::random(6));
                } while (Student::where('parent_code', $code)->exists());

                $student->update(['parent_code' => $code]);
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
