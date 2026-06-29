<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('competitions') || !Schema::hasColumn('competitions', 'age_group')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `competitions` MODIFY `age_group` VARCHAR(20) NOT NULL');
        } else {
            Schema::table('competitions', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('age_group', 20)->change();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('competitions') || !Schema::hasColumn('competitions', 'age_group')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `competitions` MODIFY `age_group` INT NOT NULL');
        } else {
            Schema::table('competitions', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->integer('age_group')->change();
            });
        }
    }
};

