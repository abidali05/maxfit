<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (!Schema::hasColumn('competitions', 'last_date')) {
                $table->date('last_date')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (Schema::hasColumn('competitions', 'last_date')) {
                $table->dropColumn('last_date');
            }
        });
    }
};
