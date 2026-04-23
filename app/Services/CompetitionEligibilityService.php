<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CompetitionEligibilityService
{
    private function normalizeGenzValue(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = strtolower(str_replace([' ', '_', '-'], '', trim($value)));
        if ($normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, 'fatherfit')) {
            return 'fatherfit';
        }

        if (str_starts_with($normalized, 'motherfit')) {
            return 'motherfit';
        }

        return $normalized;
    }

    public function query(array $filters = []): Builder
    {
        $query = User::query()
            ->where('role', 'user');

        if (Schema::hasColumn('users', 'status')) {
            $query->where('status', 'active');
        }

        if (!empty($filters['age_group'])) {
            $parsed = AgeGroupParser::parse($filters['age_group']);
            $query->whereNotNull('dob');
            $query->whereRaw(
                'TIMESTAMPDIFF(YEAR, dob, CURDATE()) BETWEEN ? AND ?',
                [$parsed['min'], $parsed['max']]
            );
        }

        if (!empty($filters['country'])) {
            $countryId = is_numeric($filters['country']) ? (int) $filters['country'] : null;

            if ($countryId !== null && $countryId > 0) {
                $query->whereRaw('CAST(country AS UNSIGNED) = ?', [$countryId]);
            } else {
                $query->where('country', (string) $filters['country']);
            }
        }

        if (!empty($filters['org_types']) && is_array($filters['org_types'])) {
            $orgTypes = array_values(array_filter(array_map('intval', $filters['org_types'])));
            if ($orgTypes !== []) {
                $query->where(function (Builder $orgTypeQuery) use ($orgTypes) {
                    // Preferred: validate org type through the selected organization relation.
                    $orgTypeQuery->whereHas('organisation', function (Builder $organizationQuery) use ($orgTypes) {
                        $organizationQuery->whereIn('type', $orgTypes);
                    })
                    // Fallback for legacy rows where organisation_id may be missing.
                    ->orWhereIn('organisation_type', $orgTypes);
                });
            }
        }

        $orgIds = [];
        if (!empty($filters['orgs'])) {
            $orgIds = array_values(array_filter(array_map('intval', Arr::wrap($filters['orgs']))));
        } elseif (!empty($filters['org'])) {
            $orgIds = array_values(array_filter(array_map('intval', Arr::wrap($filters['org']))));
        }

        if ($orgIds !== []) {
            $query->whereIn(DB::raw('CAST(organisation_id AS UNSIGNED)'), $orgIds);
        }

        if (!empty($filters['genz'])) {
            $normalized = $this->normalizeGenzValue((string) $filters['genz']);
            if ($normalized) {
                $query->whereRaw(
                    "LOWER(REPLACE(REPLACE(REPLACE(COALESCE(genz, ''), ' ', ''), '_', ''), '-', '')) = ?",
                    [$normalized]
                );
            }
        }

        return $query;
    }

    public function count(array $filters = []): int
    {
        return $this->query($filters)->count();
    }

    public function deriveGenzFromAgeGroup(?string $ageGroup): ?string
    {
        return AgeGroupParser::inferGenz($ageGroup);
    }
}
