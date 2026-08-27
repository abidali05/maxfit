<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            if (!Schema::hasColumn('goals', 'set_id')) {
                $table->foreignId('set_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('goals', 'start_date')) {
                $table->date('start_date')->nullable()->after('value');
            }
            if (!Schema::hasColumn('goals', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('goals', 'days')) {
                $table->longText('days')->nullable()->after('end_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('goals', 'days')) $columns[] = 'days';
            if (Schema::hasColumn('goals', 'end_date')) $columns[] = 'end_date';
            if (Schema::hasColumn('goals', 'start_date')) $columns[] = 'start_date';
            if (Schema::hasColumn('goals', 'set_id')) $columns[] = 'set_id';
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
