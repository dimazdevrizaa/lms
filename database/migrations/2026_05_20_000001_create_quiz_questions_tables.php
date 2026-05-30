<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add type column to assignments table
        Schema::table('assignments', function (Blueprint $table) {
            $table->enum('type', ['pdf', 'online'])->default('pdf')->after('meeting_id');
        });

        // Questions table
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->enum('type', ['pilihan_ganda', 'isian_singkat', 'essay']);
            $table->text('body'); // The question text
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('points')->default(1);
            $table->text('correct_answer')->nullable(); // For isian_singkat
            $table->timestamps();
        });

        // Question options table (for pilihan_ganda)
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->char('label', 1); // A, B, C, D, E
            $table->text('body'); // Option text
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        // Question answers table (student answers)
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('assignment_submission_id')->constrained('assignment_submissions')->cascadeOnDelete();
            $table->text('answer_text')->nullable(); // For essay/isian_singkat
            $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->nullOnDelete();
            $table->boolean('is_correct')->nullable(); // null = not graded yet
            $table->unsignedInteger('score')->nullable(); // Points earned
            $table->timestamps();

            $table->unique(['question_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_answers');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
