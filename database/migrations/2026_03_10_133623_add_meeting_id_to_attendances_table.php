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
        Schema::table('attendances', function (Blueprint $table) {
            // Add individual indexes first so FKs doesn't complain when unique is dropped
            // Check if they exist or just add them (Laravel might error if they exist, but let's be safe)
            $table->index('class_id');
            $table->index('subject_id');
            
            $table->dropUnique(['class_id', 'subject_id', 'date']);
            
            if (!Schema::hasColumn('attendances', 'meeting_id')) {
                $table->foreignId('meeting_id')->nullable()->after('subject_id')->constrained('meetings')->cascadeOnDelete();
            }
            
            $table->unique('meeting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique(['meeting_id']);
            $table->dropConstrainedForeignId('meeting_id');
            
            $table->unique(['class_id', 'subject_id', 'date']);
            
            $table->dropIndex(['class_id']);
            $table->dropIndex(['subject_id']);
        });
    }
};
