<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competition_users', function (Blueprint $table) {
            if (!Schema::hasColumn('competition_users', 'competition_id')) {
                $table->unsignedBigInteger('competition_id')->nullable()->after('competition_detail_id');
                $table->index('competition_id');
            }
        });

        DB::statement('UPDATE competition_users cu INNER JOIN competition_details cd ON cd.id = cu.competition_detail_id SET cu.competition_id = cd.competition_id WHERE cu.competition_id IS NULL');

        $foreignKeys = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'competition_users' AND COLUMN_NAME = 'competition_id' AND REFERENCED_TABLE_NAME = 'competitions'"))
            ->pluck('CONSTRAINT_NAME')
            ->all();

        if (empty($foreignKeys)) {
            Schema::table('competition_users', function (Blueprint $table) {
                $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('competition_users', function (Blueprint $table) {
            if (Schema::hasColumn('competition_users', 'competition_id')) {
                try {
                    $table->dropForeign(['competition_id']);
                } catch (\Throwable $e) {
                }

                try {
                    $table->dropIndex(['competition_id']);
                } catch (\Throwable $e) {
                }

                $table->dropColumn('competition_id');
            }
        });
    }
};
