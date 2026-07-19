<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('type')->default('pdf')->change();
            $table->string('quiz_url')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn('quiz_url');
            // We do not revert type column to enum because enum -> string change is destructive in reverse if external types exist
        });
    }
};
