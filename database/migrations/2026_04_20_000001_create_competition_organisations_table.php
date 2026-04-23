<?php

use App\Models\Competition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('competition_organisations')) {
            Schema::create('competition_organisations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('competition_id');
                $table->unsignedBigInteger('organisation_id');
                $table->timestamps();
                $table->unique(['competition_id', 'organisation_id']);
                $table->index('competition_id');
                $table->index('organisation_id');
            });
        }

        if (Schema::hasColumn('competitions', 'org')) {
            Competition::query()
                ->whereNotNull('org')
                ->chunkById(100, function ($competitions) {
                    foreach ($competitions as $competition) {
                        DB::table('competition_organisations')->updateOrInsert(
                            [
                                'competition_id' => $competition->id,
                                'organisation_id' => $competition->org,
                            ],
                            [
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_organisations');
    }
};

