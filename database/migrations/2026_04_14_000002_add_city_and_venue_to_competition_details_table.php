<?php

use App\Models\City;
use App\Models\CompetitionDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competition_details', function (Blueprint $table) {
            if (!Schema::hasColumn('competition_details', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable()->after('coach_id');
            }

            if (!Schema::hasColumn('competition_details', 'venue_id')) {
                $table->unsignedBigInteger('venue_id')->nullable()->after('city_id');
            }
        });

        if (Schema::hasColumn('competition_details', 'city') && Schema::hasColumn('competition_details', 'city_id')) {
            CompetitionDetail::query()->whereNull('city_id')->chunkById(100, function ($details) {
                foreach ($details as $detail) {
                    $cityName = trim((string) $detail->city);
                    if ($cityName === '') {
                        continue;
                    }

                    $city = City::query()->whereRaw('LOWER(name) = ?', [mb_strtolower($cityName)])->first();
                    if ($city) {
                        $detail->city_id = $city->id;
                        $detail->saveQuietly();
                    }
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('competition_details', function (Blueprint $table) {
            if (Schema::hasColumn('competition_details', 'venue_id')) {
                $table->dropColumn('venue_id');
            }

            if (Schema::hasColumn('competition_details', 'city_id')) {
                $table->dropColumn('city_id');
            }
        });
    }
};
