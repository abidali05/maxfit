<?php

namespace App\Http\Controllers\API;

use App\Models\Competition;
use App\Models\Set;
use Illuminate\Http\Request;
use App\Models\CompetitionUser;
use App\Models\RulesOfCounting;
use App\Models\CompetitionAppeal;
use App\Models\CompetitionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CompetitionResult;
use App\Models\CompetitionResultVideo;
use App\Models\CompetitionUserTotal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CompetitionController extends Controller
{

    public function getCompetition()
    {
        $authId = Auth::id();

        $with = [
            'details.coach',
            'details.venueRelation',
            'videos',
            'exercises',
            'organisation',
            'organisationType',
        ];

        if (Schema::hasTable('competition_organisations')) {
            $with[] = 'organisations';
        }

        if (Schema::hasTable('competition_organisation_types')) {
            $with[] = 'organizationTypes';
        }

        $competitions = Competition::with($with)
            ->where('status', 'active')
            ->get();

        $genzValues = $competitions
            ->map(fn($competition) => strtolower((string) $competition->getRawOriginal('genz')))
            ->filter()
            ->unique()
            ->values();

        $setsByGenz = collect();

        if ($genzValues->isNotEmpty()) {
            $setsByGenz = Set::query()
                ->with(['setExercises.exercise'])
                ->whereIn(DB::raw('LOWER(genz)'), $genzValues->all())
                ->orderBy('id')
                ->get()
                ->groupBy(fn($set) => strtolower((string) $set->getRawOriginal('genz')));
        }

        $competitions = $competitions
            ->map(function ($competition) use ($authId, $setsByGenz) {

                $competition->status_type = DB::table('competition_users')
                    ->leftJoin(
                        'competition_details',
                        'competition_users.competition_detail_id',
                        '=',
                        'competition_details.id'
                    )
                    ->where('competition_users.user_id', $authId)
                    ->where(function ($query) use ($competition) {
                        $query->where('competition_users.competition_id', $competition->id)
                            ->orWhere('competition_details.competition_id', $competition->id);
                    })
                    ->value('competition_users.status');

                $primaryDetail = $competition->relationLoaded('details')
                    ? $competition->details->first()
                    : null;

                $competition->setAttribute('venue_id', $primaryDetail?->venue_id);
                $competition->setAttribute('venue_name', $primaryDetail?->venueRelation?->name);

                $countryId = $competition->country;
                $countryName = $competition->country_name ?? null;

                $competition->setAttribute('country_id', $countryId);
                $competition->setAttribute('country', $countryName);
                $competition->makeHidden(['country_name']);

                $organizationTypes = $competition->relationLoaded('organizationTypes')
                    ? $competition->organizationTypes
                    ->map(fn($type) => [
                        'id' => $type->id,
                        'name' => $type->name,
                    ])
                    ->values()
                    : collect();

                if (
                    $organizationTypes->isEmpty()
                    && $competition->relationLoaded('organisationType')
                    && $competition->organisationType
                ) {
                    $organizationTypes = collect([
                        [
                            'id' => $competition->organisationType->id,
                            'name' => $competition->organisationType->name,
                        ],
                    ]);
                }

                $organizations = $competition->relationLoaded('organisations')
                    ? $competition->organisations
                    ->map(fn($org) => [
                        'id' => $org->id,
                        'name' => $org->name,
                    ])
                    ->values()
                    : collect();

                if (
                    $organizations->isEmpty()
                    && $competition->relationLoaded('organisation')
                    && $competition->organisation
                ) {
                    $organizations = collect([
                        [
                            'id' => $competition->organisation->id,
                            'name' => $competition->organisation->name,
                        ],
                    ]);
                }

                $competition->setAttribute('organization_types', $organizationTypes);
                $competition->setAttribute('organizations', $organizations);

                return $competition;
            })
            ->filter(function ($competition) {
                return strtolower((string) $competition->status_type) !== 'accepted';
            })
            ->values();

        return $this->success([
            'competitions' => $competitions,
        ], 'Competitions fetched successfully', 200);
    }

    public function getCompetitionStatus($status)
    {
        $authId = Auth::id();

        // Map numeric status to text
        $statusText = match ((int) $status) {
            1 => 'accepted',
            3 => 'completed',
            default => 'rejected',
        };

        $competitions = CompetitionUser::with([
            'competitionDetail.competition',
            'competition'
        ])
            ->where('user_id', $authId)
            ->where('status', $statusText)
            ->get();


        return $this->success([
            'status_text'  => $statusText,
            'competitions' => $competitions,
        ], 'Competitions fetched successfully', 200);
    }

    public function competitionDetail($id)
    {
        $competition = Competition::with(['details.coach', 'details.venueRelation'])->findOrFail($id);
        $genzForFilter = $competition->genz_key;
        if (empty($genzForFilter)) {
            $genzForFilter = strtolower(str_replace([' ', '_', '-'], '', (string) $competition->genz));
            if ($genzForFilter === 'fatherfit') {
                $genzForFilter = 'fatherfits';
            } elseif ($genzForFilter === 'motherfit') {
                $genzForFilter = 'motherfits';
            }
        }

        $authUser = Auth::user();
        $latestAssessment = $authUser?->physical_assessment()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();
        $fitnessLevel = $this->normalizeFitnessLevel($latestAssessment?->exercise_type);
        $gender = $this->normalizeGender($authUser?->gender ?: $latestAssessment?->gender);

        // Load exercises with their sets
        $exercises = $competition->exercises()
            ->with(['sets' => function ($setQuery) use ($genzForFilter, $fitnessLevel, $gender) {
                $setQuery->select('sets.id', 'sets.name', 'sets.genz', 'sets.fitness_level', 'sets.gender');
                if (!empty($genzForFilter)) {
                    $setQuery->where('sets.genz', $genzForFilter);
                }
                if (!empty($fitnessLevel) && !empty($gender)) {
                    $setQuery->where('sets.fitness_level', $fitnessLevel)
                        ->where('sets.gender', $gender);
                }
            }])
            ->where(function ($query) use ($genzForFilter) {
                if (!empty($genzForFilter)) {
                    $query->where('genz', $genzForFilter)->orWhere('genz', 'both');
                    return;
                }

                $query->where('genz', 'both');
            })
            ->when(!empty($fitnessLevel) && !empty($gender), function ($query) use ($genzForFilter, $fitnessLevel, $gender) {
                $query->whereHas('sets', function ($setQuery) use ($genzForFilter, $fitnessLevel, $gender) {
                    if (!empty($genzForFilter)) {
                        $setQuery->where('sets.genz', $genzForFilter);
                    }
                    $setQuery->where('sets.fitness_level', $fitnessLevel)
                        ->where('sets.gender', $gender);
                });
            })
            ->get()
            ->filter(function ($exercise) {
                return $exercise->sets->isNotEmpty();
            })
            ->values();

        $competitionDetailIds = CompetitionDetail::where('competition_id', $competition->id)
            ->pluck('id');

        $competitionUserIds = CompetitionUser::whereIn('competition_detail_id', $competitionDetailIds)
            ->pluck('id');

        $competitionUserTotals = CompetitionUserTotal::whereIn('competition_user_id', $competitionUserIds)
            ->with('competitionUser.user')
            ->get();

        $competitionResultIds = CompetitionResult::whereIn('competition_user_id', $competitionUserIds)
            ->pluck('id');

        $competitionResults = CompetitionResult::with(['exercise'])
            ->whereIn('competition_user_id', $competitionUserIds)
            ->get()
            ->groupBy('competition_user_id');

        $competitionResultVideos = CompetitionResultVideo::with('result.competitionUser.user')
            ->whereIn('competition_result_id', $competitionResultIds)
            ->get();

        // Ã¢Å“â€¦ Map results grouped by set
        $results = $competitionUserTotals->map(function ($total) use ($exercises, $competitionResults, $genzForFilter, $fitnessLevel, $gender) {
            $userResults = $competitionResults[$total->competition_user_id] ?? collect();

            // First, build an array where each set has its exercises
            $setsData = collect();

            foreach ($userResults as $res) {
                $exercise = $exercises->firstWhere('id', $res->exercise->id);
                if (!$exercise) continue;

                foreach ($exercise->sets as $set) {
                    if (!empty($genzForFilter) && strtolower((string) $set->genz) !== strtolower((string) $genzForFilter)) {
                        continue;
                    }
                    if (!empty($fitnessLevel) && !empty($gender)) {
                        if ((string) $set->fitness_level !== (string) $fitnessLevel || (string) $set->gender !== (string) $gender) {
                            continue;
                        }
                    }

                    if (!$setsData->has($set->id)) {
                        $setsData->put($set->id, [
                            'id' => $set->id,
                            'name' => $set->name,
                            'exercises' => [],
                        ]);
                    }

                    $setItem = $setsData->get($set->id);
                    $setItem['exercises'][] = [
                        'id' => $res->exercise->id,
                        'name' => $res->exercise->name,
                        'score' => $res->score ?? $res->pivot->score ?? null,
                    ];
                    $setsData->put($set->id, $setItem);
                }
            }

            return [
                'user_id'     => $total->competitionUser->user->id ?? null,
                'user_image'  => $total->competitionUser->user->image ?? null,
                'user_name'   => $total->competitionUser->user->name ?? null,
                'rank'        => $total->rank,
                'sets'        => $setsData->sortBy('id')->values(), // return sets ordered by set id
            ];
        });

        $response = [
            'competition'        => $competition,
            'competition_details'=> $competition->details ?? collect(),
            'exercises'          => $exercises,
            'criteria'           => [
                'genz' => $genzForFilter,
                'fitness_level' => $fitnessLevel,
                'gender' => $gender,
            ],
            'results'            => $results,
            'challenging_videos' => $competitionResultVideos->map(function ($video) {
                return [
                    'id'           => $video->id,
                    'youtube_link' => $video->youtube_link,
                    'user_name'    => $video->result->competitionUser->user->name ?? null,
                    'user_image'   => $video->result->competitionUser->user->image ?? null,
                ];
            }),
        ];

        return $this->success($response, 'Competition details fetched successfully', 200);
    }

    private function normalizeGender(?string $gender): ?string
    {
        $value = strtolower(trim((string) $gender));
        return match ($value) {
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
            default => null,
        };
    }

    private function normalizeFitnessLevel(?string $value): ?string
    {
        $normalized = strtolower(trim((string) $value));
        return match ($normalized) {
            'expert' => 'Expert',
            'immature' => 'Immature',
            default => null,
        };
    }

    public function getCompetitionDetail($id)
    {
        $competition = Competition::with(['details', 'videos', 'exercises' => function ($q) {
            $q->select('exercises.id', 'exercise_category_id', 'name', 'genz', 'description', 'image');
        }])
            ->where('status', 'active')
            ->findOrFail($id);

        return $this->success([
            'competition' => $competition
        ], 'Competition detail fetched successfully', 200);
    }


    // public function acceptOrReject(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:accepted,rejected',
    //     ]);

    //     $competition = Competition::where('status', 'active')->findOrFail($id);
    //     $userId = Auth::id();

    //     $competitionUser = CompetitionUser::query()
    //         ->where('user_id', $userId)
    //         ->where(function ($query) use ($competition) {
    //             $query->where('competition_id', $competition->id)
    //                 ->orWhereIn('competition_detail_id', function ($subQuery) use ($competition) {
    //                     $subQuery->select('id')
    //                         ->from('competition_details')
    //                         ->where('competition_id', $competition->id);
    //                 });
    //         })
    //         ->first();

    //     if ($competitionUser) {
    //         $competitionUser->status = $request->status;
    //         $competitionUser->competition_id = $competition->id;
    //         $competitionUser->save();
    //     } else {
    //         $competitionUser = CompetitionUser::create([
    //             'user_id' => $userId,
    //             'competition_id' => $competition->id,
    //             'competition_detail_id' => null,
    //             'status' => $request->status,
    //         ]);
    //     }

    //     if ($competitionUser) {
    //         return $this->success($competitionUser, 'Competition status updated successfully', 200);
    //     }

    //     return $this->error('Competition not found', [], 404);
    // }

    public function acceptOrReject(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        $competition = Competition::where('status', 'active')->findOrFail($id);
        $userId = Auth::id();

        $competitionUser = CompetitionUser::updateOrCreate(
            [
                'competition_id' => $competition->id,
                'user_id' => $userId,
            ],
            [
                'status' => $request->status,
                'competition_detail_id' => null,
            ]
        );

        return $this->success($competitionUser, 'Competition status updated successfully', 200);
    }



    public function getResult()
    {
        $authId = Auth::id();

        // 1. Get all active competition_detail_ids where auth user is participating
        $activeCompetitionDetailIds = CompetitionUser::where('user_id', $authId)
            ->where('status', 'accepted')
            ->pluck('competition_detail_id');

        if ($activeCompetitionDetailIds->isEmpty()) {
            return $this->success([
                'overall_summary' => [],
                'exercise_summary' => [],
            ], 'No competition results found', 200);
        }

        // 2. Get latest competition_detail_id from above (based on competitions.created_at)
        $latestDetail = CompetitionDetail::whereIn('id', $activeCompetitionDetailIds)
            ->with('competition')
            ->orderByDesc('created_at')
            ->first();

        if (!$latestDetail) {
            return $this->success([
                'overall_summary' => [],
                'exercise_summary' => [],
            ], 'No competition results found', 200);
        }

        // 3. Get all users for the latest competition_detail (with totals)
        $latestCompetitionUsers = CompetitionUser::with([
            'user',
            'total',  // hasOne CompetitionUserTotal
        ])
            ->where('competition_detail_id', $latestDetail->id)
            ->get();

        $overallSummary = $latestCompetitionUsers
            ->map(function ($cu) use ($latestDetail) {
                $totalScore = $cu->total->total_score
                    ?? $cu->results->sum('score'); // fallback if no totals yet

                return [
                    'competition_id' => $latestDetail->competition_id,
                    'user_id' => $cu->user->id,
                    'name' => $cu->user->name ?? 'Unknown',
                    'image' => $cu->user->image ?? '',
                    'total_score' => $totalScore,
                    'rank' => $cu->total->rank ?? null,
                ];
            })
            ->sortBy('rank')
            ->values();


        // 4. Exercise summary for ALL competitions the user is in
        $allCompetitionUsers = CompetitionUser::with([
            'user',
            'results.exercise', // get exercise name
            'competitionDetail.competition'
        ])
            ->whereIn('competition_detail_id', $activeCompetitionDetailIds)
            ->get();

        $exerciseSummary = $allCompetitionUsers
            ->flatMap(function ($cu) {
                return $cu->results->map(function ($result) use ($cu) {
                    return [
                        'exercise' => $result->exercise->name ?? 'Unknown Exercise',
                        'competition_id' => $cu->competitionDetail->competition_id,
                        'user_id' => $cu->user->id,
                        'name' => $cu->user->name ?? 'Unknown',
                        'image' => $cu->user->image ?? '',
                        'score' => (float) $result->score,
                    ];
                });
            })
            ->groupBy('exercise')
            ->map(function ($group, $exerciseName) {
                return [
                    'exercise' => $exerciseName,
                    'participants' => $group->values()
                ];
            })
            ->values();

        return $this->success([
            'result' => $overallSummary,
            'summary' => $exerciseSummary,
        ], 'Competition summary data', 200);
    }

    public function writeAppeal(Request $request)
    {
        $request->validate([
            'competition_result_video_id' => 'required|exists:competition_result_videos,id',
            'appeal_text' => 'required|string|max:1000',
        ]);

        $competitionVideo = CompetitionResultVideo::findOrFail($request->competition_result_video_id);

        // $competitionDetailId = CompetitionDetail::where('competition_id', $competitionVideo->competition_id)
        //     ->value('id');

        // if (!$competitionDetailId) {
        //     return $this->error('Competition detail not found for this video', [], 404);
        // }

        $storeAppeal = CompetitionAppeal::create([
            'user_id' => Auth::id(),
            'competition_video_id' => $competitionVideo->id,
            'appeal_text' => $request->appeal_text,
            'status' => 'pending',
        ]);

        if (!$storeAppeal) {
            return $this->error('Failed to submit appeal', [], 500);
        }

        return $this->success('Appeal submitted successfully', 200);
    }

    public function getAppeal($id)
    {
        $authId = Auth::id();

        $competition = Competition::with([
            'details.competitionUsers.results.videos.result_appel',
        ])->findOrFail($id);

        $appels = $competition->details
            ->flatMap(function ($detail) use ($authId, $competition) {
                return $detail->competitionUsers
                    ->flatMap(fn($user) => $user->results)
                    ->flatMap(fn($result) => $result->videos)
                    ->flatMap(fn($video) => $video->result_appel ?? [])
                    ->filter(fn($appel) => $appel->user_id == $authId)
                    ->map(function ($appel) use ($detail, $competition) {
                        return [
                            'id' => $appel->id,
                            'user_id' => $appel->user_id,
                            'competition_video_id' => $appel->competition_video_id,
                            'appeal_text' => $appel->appeal_text,
                            'status' => $appel->status,
                            'created_at' => $appel->created_at,
                            'updated_at' => $appel->updated_at,
                            'competition_name' => $competition->name ?? null, // ðŸ‘ˆ Competition name
                            'competition_image' => $detail->image ?? null,     // ðŸ‘ˆ Image from detail
                        ];
                    });
            })
            ->values();

        return response()->json([
            'appels' => $appels,
        ]);
    }


    public function viewResult($competitionDetailId)
    {
        $competitionDetail = CompetitionDetail::with([
            'competition:id,name',
            'competitionUsers.user:id,name,email,image',
            'competitionUsers.total' // from competition_user_totals table
        ])->findOrFail($competitionDetailId);

        return response()->json([
            'success' => true,
            'message' => 'Competition detail result fetched successfully',
            'data' => [
                'competition_detail' => [
                    'id' => $competitionDetail->id,
                    'competition' => [
                        'id' => $competitionDetail->competition->id,
                        'title' => $competitionDetail->competition->name
                    ],
                    'coach_name' => $competitionDetail->coach_name,
                    'city' => $competitionDetail->city,
                    'start_date' => $competitionDetail->start_date,
                    'end_date' => $competitionDetail->end_date
                ],
                'users' => $competitionDetail->competitionUsers->map(function ($compUser) {
                    return [
                        'id' => $compUser->user->id,
                        'name' => $compUser->user->name,
                        'email' => $compUser->user->email,
                        'image' => $compUser->user->image ? asset('storage/' . $compUser->user->image) : null,
                        'score' => $compUser->total->total_score ?? null,
                        'rank' => $compUser->total->rank ?? null
                    ];
                })
            ]
        ]);
    }



    public function RulesOfCount()
    {
        $data = RulesOfCounting::with('competition')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 201);
    }

    public function RulesOfCountDetail($id)
    {
        $data = RulesOfCounting::findOrFail($id)->with('competition')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 201);
    }
}
