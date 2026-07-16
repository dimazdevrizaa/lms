<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Generate slugs for existing classes
        $classes = DB::table('classes')->get();
        foreach ($classes as $c) {
            $slug = Str::slug($c->name);
            $count = DB::table('classes')->where('slug', $slug)->count();
            if ($count > 0) {
                $slug .= '-' . $c->id;
            }
            DB::table('classes')->where('id', $c->id)->update(['slug' => $slug]);
        }

        // Generate slugs for existing subjects
        $subjects = DB::table('subjects')->get();
        foreach ($subjects as $s) {
            $slug = Str::slug($s->name);
            $count = DB::table('subjects')->where('slug', $slug)->count();
            if ($count > 0) {
                $slug .= '-' . $s->id;
            }
            DB::table('subjects')->where('id', $s->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
