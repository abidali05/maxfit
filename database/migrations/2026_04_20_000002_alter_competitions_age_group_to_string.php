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

        DB::statement('ALTER TABLE `competitions` MODIFY `age_group` VARCHAR(20) NOT NULL');
    }

    public function down(): void
    {
        if (!Schema::hasTable('competitions') || !Schema::hasColumn('competitions', 'age_group')) {
            return;
        }

        DB::statement('ALTER TABLE `competitions` MODIFY `age_group` INT NOT NULL');
    }
};

