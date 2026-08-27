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
        Schema::table('group_exercises', function (Blueprint $table) {
            if (!Schema::hasColumn('group_exercises', 'day')) {
                $table->string('day')->nullable()->after('end_date'); // Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday
            }
            if (!Schema::hasColumn('group_exercises', 'order')) {
                $table->integer('order')->default(0)->after('day');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_exercises', function (Blueprint $table) {
            if (Schema::hasColumn('group_exercises', 'order')) {
                $table->dropColumn('order');
            }
            if (Schema::hasColumn('group_exercises', 'day')) {
                $table->dropColumn('day');
            }
        });
    }
};
