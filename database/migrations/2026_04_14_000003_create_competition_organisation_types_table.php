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
        if (!Schema::hasTable('competition_organisation_types')) {
            Schema::create('competition_organisation_types', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('competition_id');
                $table->unsignedBigInteger('organisation_type_id');
                $table->timestamps();
                $table->unique(['competition_id', 'organisation_type_id']);
                $table->index('competition_id');
                $table->index('organisation_type_id');
            });
        }

        if (Schema::hasColumn('competitions', 'org_type')) {
            Competition::query()
                ->whereNotNull('org_type')
                ->chunkById(100, function ($competitions) {
                    foreach ($competitions as $competition) {
                        DB::table('competition_organisation_types')->updateOrInsert(
                            [
                                'competition_id' => $competition->id,
                                'organisation_type_id' => $competition->org_type,
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
        Schema::dropIfExists('competition_organisation_types');
    }
};
