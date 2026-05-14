<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            if (!Schema::hasColumn('exercises', 'fitness_level')) {
                $table->string('fitness_level')->nullable()->after('exercise_type');
            }

            if (!Schema::hasColumn('exercises', 'gender')) {
                $table->string('gender')->nullable()->after('fitness_level');
            }
        });

        Schema::table('sets', function (Blueprint $table) {
            if (!Schema::hasColumn('sets', 'fitness_level')) {
                $table->string('fitness_level')->nullable()->after('genz');
            }

            if (!Schema::hasColumn('sets', 'gender')) {
                $table->string('gender')->nullable()->after('fitness_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sets', function (Blueprint $table) {
            if (Schema::hasColumn('sets', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('sets', 'fitness_level')) {
                $table->dropColumn('fitness_level');
            }
        });

        Schema::table('exercises', function (Blueprint $table) {
            if (Schema::hasColumn('exercises', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('exercises', 'fitness_level')) {
                $table->dropColumn('fitness_level');
            }
        });
    }
};
