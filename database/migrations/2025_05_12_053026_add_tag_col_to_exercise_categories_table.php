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
        Schema::table('exercise_categories', function (Blueprint $table) {
            $table->string('tag')->after('name')->nullable();
            $table->string('overall_time')->after('tag')->nullable();
            $table->string('over_kcal')->after('overall_time')->nullable();
            $table->string('exerice_lvl')->after('over_kcal')->nullable();
            $table->longText('description')->after('exerice_lvl')->nullable();
            $table->string('image')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercise_categories', function (Blueprint $table) {
            if (Schema::hasColumn('exercise_categories', 'tag')) {
                $table->dropColumn('tag');
            }
            if (Schema::hasColumn('exercise_categories', 'overall_time')) {
                $table->dropColumn('overall_time');
            }
            if (Schema::hasColumn('exercise_categories', 'over_kcal')) {
                $table->dropColumn('over_kcal');
            }
            if (Schema::hasColumn('exercise_categories', 'exerice_lvl')) {
                $table->dropColumn('exerice_lvl');
            }
            if (Schema::hasColumn('exercise_categories', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('exercise_categories', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
