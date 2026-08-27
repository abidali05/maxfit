<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Goal;
use App\Models\User;
use App\Models\DailyAssessment;
use App\Models\GoalSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserExerciseAssessment;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    private function mapTodayExercisePayload($goalItem, ?int $sequence, float $todayCount, $lastLoggedAt): array
    {
        $exercise = $goalItem->exercise;
        $rawType = strtolower($exercise->exercise_type ?? 'count');
        $isSec = str_contains($rawType, 'sec');
        $unit = $isSec ? 'per sec' : 'per count';
        $formattedType = $isSec ? 'per second' : 'per count';

        $exerciseData = null;
        if ($exercise) {
            $exerciseData = [
                'id' => $exercise->id,
                'name' => $exercise->name,
                'category' => $exercise->exercise_category->name ?? 'N/A',
                'image' => $exercise->image ? url('storage/' . $exercise->image) : asset('assets/images/user.jpg'),
                'exercise_type' => $formattedType,
                'unit' => $unit,
                'description' => $exercise->description ?? '',
                'youtubeLink' => $exercise->youtube_link ?? '',
            ];
        }

        return [
            'goal_id' => $goalItem->id,
            'exercise_id' => (int) $goalItem->exercise_id,
            'value' => (string) $goalItem->value,
            'target_count' => (int) $goalItem->value,
            'count' => (int) $todayCount,
            'today_count' => (int) $todayCount,
            'sequence' => $sequence,
            'is_submitted' => $todayCount > 0,
            'marked_today' => $todayCount > 0,
            'last_logged_at' => $lastLoggedAt,
            'exercise_type' => $formattedType,
            'unit' => $unit,
            'exercise' => $exerciseData ?? $goalItem->exercise,
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id',
            'exercise' => 'required|array',
            'exercise.*' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        try {
            $userId = $request->userId;
            $exerciseData = $request->input('exercise');

            $insertData = [];

            foreach ($exerciseData as $exerciseId => $value) {
                $insertData[] = [
                    'user_id' => $userId,
                    'exercise_id' => $exerciseId,
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Goal::insert($insertData);

            User::where('id', $userId)->update([
                'goal_setting' => true,
                'profile_step' => "5"
            ]);

            return $this->success(null, 'User Exercise Goals added successfully', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), [], 500);
        }
    }

    public function storeGoalWithDate(Request $request)
    {
        $rules = [
            'userId' => 'required|exists:users,id',
            'skip'   => 'nullable|in:yes',
        ];

        if ($request->skip !== 'yes') {
            $rules['start_date']                            = 'required|date';
            $rules['end_date']                              = 'required|date|after_or_equal:start_date';
            $rules['sets']                                  = 'required|array|min:1';
            $rules['sets.*.set_id']                         = 'required|exists:sets,id';
            $rules['sets.*.days']                           = 'nullable|array';
            $rules['sets.*.exercises']                      = 'nullable|array';
            $rules['sets.*.exercise_details']               = 'nullable|array';
            $rules['sets.*.exercise_details.*.exercise_id'] = 'required_with:sets.*.exercise_details|exists:exercises,id';
            $rules['sets.*.exercise_details.*.value']       = 'nullable';
            $rules['sets.*.exercise_details.*.days']        = 'nullable|array';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        try {
            $userId = (int) $request->userId;
            $skip = $request->skip === 'yes';

            if ($skip) {
                User::where('id', $userId)->update([
                    'is_skip' => 1,
                    'goal_setting' => false,
                ]);

                return $this->success(null, 'Goal setting skipped successfully', 200);
            }

            foreach ($request->sets as $set) {
                $setId = (int) $set['set_id'];
                $setDays = $set['days'] ?? [];

                // 1. If exercise_details is provided, use per-exercise specific days
                if (!empty($set['exercise_details']) && is_array($set['exercise_details'])) {
                    foreach ($set['exercise_details'] as $detail) {
                        $exerciseId = (int) ($detail['exercise_id'] ?? 0);
                        $value = $detail['value'] ?? null;
                        $exerciseDays = !empty($detail['days']) ? $detail['days'] : $setDays;

                        if (!$exerciseId || $value === null) {
                            continue;
                        }

                        Goal::updateOrCreate(
                            [
                                'user_id'     => $userId,
                                'set_id'      => $setId,
                                'exercise_id' => $exerciseId,
                            ],
                            [
                                'value'       => (string) $value,
                                'days'        => json_encode(array_values($exerciseDays)),
                                'start_date'  => $request->start_date,
                                'end_date'    => $request->end_date,
                                'updated_at'  => now(),
                            ]
                        );
                    }
                } 
                // 2. Fallback to legacy exercises object if exercise_details is not provided
                elseif (!empty($set['exercises']) && is_array($set['exercises'])) {
                    $daysJson = json_encode(array_values($setDays));
                    foreach ($set['exercises'] as $exerciseId => $value) {
                        if ($value === null) {
                            continue;
                        }

                        Goal::updateOrCreate(
                            [
                                'user_id'     => $userId,
                                'set_id'      => $setId,
                                'exercise_id' => (int) $exerciseId,
                            ],
                            [
                                'value'       => (string) $value,
                                'days'        => $daysJson,
                                'start_date'  => $request->start_date,
                                'end_date'    => $request->end_date,
                                'updated_at'  => now(),
                            ]
                        );
                    }
                }
            }

            User::where('id', $userId)->update([
                'goal_setting' => true,
                'profile_step' => "5"
            ]);

            return $this->success(null, 'Goals saved/updated set & day wise successfully', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), [], 500);
        }
    }


    public function getTodayGoals(Request $request)
    {
        try {
            $user = Auth::user();

            $targetDate = $request->query('date', Carbon::today()->toDateString());
            try {
                $cDate = Carbon::parse($targetDate);
                $targetDate = $cDate->toDateString();
                $targetDayShort = $cDate->format('D'); // Mon, Tue...
                $targetDayFull = $cDate->format('l'); // Monday...
            } catch (\Exception $e) {
                $targetDate = Carbon::today()->toDateString();
                $targetDayShort = Carbon::today()->format('D');
                $targetDayFull = Carbon::today()->format('l');
            }

            $dayMap = [
                'Mon' => 'M',
                'Tue' => 'T',
                'Wed' => 'W',
                'Thu' => 'Th',
                'Fri' => 'F',
                'Sat' => 'S',
                'Sun' => 'Su'
            ];

            $dayCode = $dayMap[$targetDayShort] ?? $targetDayShort;

            // Fetch goals active for targetDate containing the dayCode or dayName
            $goals = Goal::where('user_id', $user->id)
                ->whereDate('start_date', '<=', $targetDate)
                ->whereDate('end_date', '>=', $targetDate)
                ->where(function ($q) use ($dayCode, $targetDayFull, $targetDayShort) {
                    $q->whereJsonContains('days', $dayCode)
                      ->orWhereJsonContains('days', $targetDayFull)
                      ->orWhereJsonContains('days', $targetDayShort)
                      ->orWhereJsonContains('days', 'Everyday')
                      ->orWhereNull('days');
                })
                ->with(['exercise.exercise_category', 'set.setExercises' => function ($query) {
                    $query->orderBy('sequence');
                }])
                ->get()
                ->groupBy('set_id');

            // Today's logged assessment counts
            $todayAssessments = DailyAssessment::where('user_id', $user->id)
                ->whereDate('created_at', $targetDate)
                ->select('set_id', 'exercise_id')
                ->selectRaw('SUM(COALESCE(`count`, 0)) as total_count')
                ->selectRaw('MAX(created_at) as last_logged_at')
                ->groupBy('set_id', 'exercise_id')
                ->get();

            $totalExercisesCount = 0;
            $completedExercisesCount = 0;
            $todayTotalCount = 0;
            $todayTargetTotal = 0;

            $setsPayload = [];

            foreach ($goals as $setId => $items) {
                $set = ($setId && $items->first()->set) ? $items->first()->set : null;
                $setExercises = $set ? $set->setExercises->pluck('sequence', 'exercise_id') : collect([]);

                $sortedItems = $items->sortBy(function ($item) use ($setExercises) {
                    return $setExercises[$item->exercise_id] ?? 999;
                });

                $exercisePayload = $sortedItems->map(function ($item) use ($setExercises, $todayAssessments, &$completedExercisesCount, &$todayTotalCount, &$todayTargetTotal) {
                    // Match assessment by set_id and exercise_id, or fallback by exercise_id
                    $todayAssessment = $todayAssessments->first(function ($a) use ($item) {
                        if (!empty($item->set_id) && !empty($a->set_id)) {
                            return (int)$a->set_id === (int)$item->set_id && (int)$a->exercise_id === (int)$item->exercise_id;
                        }
                        return (int)$a->exercise_id === (int)$item->exercise_id;
                    });

                    $count = $todayAssessment ? (float) $todayAssessment->total_count : 0.0;
                    $sequence = $setExercises[$item->exercise_id] ?? null;

                    if ($count > 0) {
                        $completedExercisesCount++;
                    }
                    $todayTotalCount += $count;
                    $todayTargetTotal += (float) ($item->value ?? 0);

                    return $this->mapTodayExercisePayload(
                        $item,
                        $sequence,
                        $count,
                        $todayAssessment->last_logged_at ?? null
                    );
                })->values();

                $totalExercisesCount += $exercisePayload->count();

                $setsPayload[] = [
                    'set_id' => $setId ? (int)$setId : null,
                    'set_name' => $set ? $set->name : null,
                    'today_total_count' => (int) $exercisePayload->sum('count'),
                    'today_target_total' => (int) $exercisePayload->sum('target_count'),
                    'exercises_count' => $exercisePayload->count(),
                    'exercises' => $exercisePayload,
                ];
            }

            $response = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'date' => $targetDate,
                'day' => $dayCode,
                'day_name' => $targetDayFull,
                'today_total_count' => (int) $todayTotalCount,
                'today_target_total' => (int) $todayTargetTotal,
                'total_exercises_count' => $totalExercisesCount,
                'completed_exercises_count' => $completedExercisesCount,
                'sets' => $setsPayload,
            ];

            return $this->success($response, 'Today workout fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), [], 500);
        }
    }

    public function getGoals()
    {
        try {
            $user = Auth::user();

            $todayDate = Carbon::today()->toDateString();
            $todayDay  = Carbon::today()->format('D');

            $dayMap = [
                'Mon' => 'M',
                'Tue' => 'T',
                'Wed' => 'W',
                'Thu' => 'Th',
                'Fri' => 'F',
                'Sat' => 'S',
                'Sun' => 'Su'
            ];

            $day = $dayMap[$todayDay] ?? $todayDay;

            $goals = Goal::where('user_id', $user->id)
                ->with(['exercise.exercise_category', 'set.setExercises' => function ($query) {
                    $query->orderBy('sequence');
                }])
                ->get()
                ->groupBy('set_id');

            $firstGoal = Goal::where('user_id', $user->id)->whereNotNull('start_date')->orderBy('id', 'desc')->first();
            $startDate = $firstGoal && $firstGoal->start_date ? Carbon::parse($firstGoal->start_date)->toDateString() : null;
            $endDate = $firstGoal && $firstGoal->end_date ? Carbon::parse($firstGoal->end_date)->toDateString() : null;

            $setsPayload = [];

            foreach ($goals as $setId => $items) {
                $set = ($setId && $items->first()->set) ? $items->first()->set : null;
                $setExercises = $set ? $set->setExercises->pluck('sequence', 'exercise_id') : collect([]);

                $firstSetGoal = $items->first();
                $setStartDate = $firstSetGoal && $firstSetGoal->start_date ? Carbon::parse($firstSetGoal->start_date)->toDateString() : $startDate;
                $setEndDate = $firstSetGoal && $firstSetGoal->end_date ? Carbon::parse($firstSetGoal->end_date)->toDateString() : $endDate;

                $sortedItems = $items->sortBy(function ($item) use ($setExercises) {
                    return $setExercises[$item->exercise_id] ?? 999;
                });

                // Collect aggregate days across all exercises in this set
                $allSetDays = [];
                $exercisesObject = [];
                $exerciseDetails = [];

                $exercisesList = $sortedItems->map(function ($item) use ($setExercises, $setStartDate, $setEndDate, &$allSetDays, &$exercisesObject, &$exerciseDetails) {
                    $rawExDays = $item->days ?? [];
                    if (is_string($rawExDays)) {
                        $decoded = json_decode($rawExDays, true);
                        $exDays = is_array($decoded) ? $decoded : [];
                    } elseif (is_array($rawExDays)) {
                        $exDays = $rawExDays;
                    } else {
                        $exDays = [];
                    }

                    $allSetDays = array_merge($allSetDays, $exDays);
                    $exercisesObject[(string)$item->exercise_id] = (string)$item->value;

                    $exercise = $item->exercise;
                    $rawType = strtolower($exercise->exercise_type ?? 'count');
                    $isSec = str_contains($rawType, 'sec');
                    $unit = $isSec ? 'per sec' : 'per count';
                    $formattedType = $isSec ? 'per second' : 'per count';

                    $exStartDate = $item->start_date ? Carbon::parse($item->start_date)->toDateString() : $setStartDate;
                    $exEndDate = $item->end_date ? Carbon::parse($item->end_date)->toDateString() : $setEndDate;

                    $exerciseDetails[] = [
                        'exercise_id' => (int) $item->exercise_id,
                        'value' => (string) $item->value,
                        'days' => $exDays,
                        'start_date' => $exStartDate,
                        'end_date' => $exEndDate,
                    ];

                    return [
                        'goal_id' => $item->id,
                        'exercise_id' => (int) $item->exercise_id,
                        'value' => (string) $item->value,
                        'days' => $exDays,
                        'start_date' => $exStartDate,
                        'end_date' => $exEndDate,
                        'sequence' => $setExercises[$item->exercise_id] ?? null,
                        'exercise_type' => $formattedType,
                        'unit' => $unit,
                        'exercise' => $exercise ? [
                            'id' => $exercise->id,
                            'name' => $exercise->name,
                            'category' => $exercise->exercise_category->name ?? 'N/A',
                            'image' => $exercise->image ? url('storage/' . $exercise->image) : asset('assets/images/user.jpg'),
                            'exercise_type' => $formattedType,
                            'unit' => $unit,
                            'description' => $exercise->description ?? '',
                            'youtubeLink' => $exercise->youtube_link ?? '',
                        ] : null,
                    ];
                })->values();

                $uniqueSetDays = array_values(array_unique($allSetDays));

                $setsPayload[] = [
                    'set_id' => $setId ? (int)$setId : null,
                    'set_name' => $set ? $set->name : null,
                    'start_date' => $setStartDate,
                    'end_date' => $setEndDate,
                    'days' => $uniqueSetDays,
                    'exercises' => $exercisesObject,
                    'exercise_details' => $exerciseDetails,
                    'exercise_list' => $exercisesList,
                ];
            }

            $response = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'date' => $todayDate,
                'day' => $day,
                'sets' => $setsPayload,
            ];

            return $this->success($response, 'Workout fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), [], 500);
        }
    }


    public function getAssesments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        try {
            $assessments = UserExerciseAssessment::where('user_id', $request->user_id)
                ->whereBetween('created_at', [$request->start_date, $request->end_date])
                ->with('exercise:id,name,exercise_type')
                ->selectRaw('exercise_id, SUM(value) as total_value, MAX(created_at) as created_at')
                ->groupBy('exercise_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'exercise_id' => $item->exercise->id,
                        'exercise_name' => $item->exercise->name,
                        'exercise_type' => $item->exercise->exercise_type,
                        'total_value' => $item->total_value,
                        'created_at' => $item->created_at
                    ];
                });

            return $this->success($assessments, 'Assessments fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), [], 500);
        }
    }
}
