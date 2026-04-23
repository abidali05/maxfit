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
        return [
            'goal_id' => $goalItem->id,
            'value' => $goalItem->value,
            'sequence' => $sequence,
            'today_count' => $todayCount,
            'marked_today' => $todayCount > 0,
            // 'last_logged_at' => $lastLoggedAt,
            'exercise' => $goalItem->exercise,
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
        $validator = Validator::make($request->all(), [
            'userId'              => 'required|exists:users,id',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after:start_date',
            'sets'                => 'required|array',
            'sets.*.set_id'       => 'required|exists:sets,id',
            'sets.*.days'         => 'required|array',
            'sets.*.exercises'    => 'required|array',
            'sets.*.exercises.*'  => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        try {
            $userId = (int) $request->userId;

            foreach ($request->sets as $set) {
                $setId = (int) $set['set_id'];
                $daysJson = json_encode($set['days']);

                foreach ($set['exercises'] as $exerciseId => $value) {

                    if ($value === null) { // || (int)$value === 0
                        continue;
                    }

                    Goal::updateOrCreate(
                        [
                            'user_id'     => $userId,
                            'set_id'      => $setId,
                            'exercise_id' => (int) $exerciseId,
                        ],
                        [
                            'value'      => $value,
                            'days'       => $daysJson,
                            'start_date'  => $request->start_date,
                            'end_date'    => $request->end_date,
                            'updated_at' => now(),
                        ]
                    );
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


    public function getTodayGoals()
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

            $day = $dayMap[$todayDay];

            $goals = Goal::where('user_id', $user->id)
                ->whereDate('start_date', '<=', $todayDate)
                ->whereDate('end_date', '>=', $todayDate)
                ->whereJsonContains('days', $day)
                ->with(['exercise', 'set.setExercises' => function ($query) {
                    $query->orderBy('sequence');
                }])
                ->get()
                ->groupBy('set_id');

            // Today's logged assessment counts grouped by set/exercise.
            $todayAssessments = DailyAssessment::where('user_id', $user->id)
                ->whereDate('created_at', $todayDate)
                ->select('set_id', 'exercise_id')
                ->selectRaw('SUM(COALESCE(`count`, 0)) as total_count')
                ->selectRaw('MAX(created_at) as last_logged_at')
                ->groupBy('set_id', 'exercise_id')
                ->get()
                ->keyBy(function ($assessment) {
                    return ($assessment->set_id ?? 'null') . '_' . $assessment->exercise_id;
                });

            $response = [
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email
                ],
                'date' => $todayDate,
                'day'  => $day,
                'sets' => []
            ];

            foreach ($goals as $setId => $items) {
                if ($setId && $items->first()->set) {
                    $set = $items->first()->set; // <-- already exists, keep this

                    // Get exercise sequence from set_exercises table
                    $setExercises = $set->setExercises->pluck('sequence', 'exercise_id');

                    // Sort exercises by sequence
                    $sortedItems = $items->sortBy(function ($item) use ($setExercises) {
                        return $setExercises[$item->exercise_id] ?? 999;
                    });

                    $exercisePayload = $sortedItems->map(function ($item) use ($setExercises, $todayAssessments) {
                        $assessmentKey = ($item->set_id ?? 'null') . '_' . $item->exercise_id;
                        $todayAssessment = $todayAssessments->get($assessmentKey);
                        $todayCount = $todayAssessment ? (float) $todayAssessment->total_count : 0.0;
                        $sequence = $setExercises[$item->exercise_id] ?? null;

                        return $this->mapTodayExercisePayload(
                            $item,
                            $sequence,
                            $todayCount,
                            $todayAssessment->last_logged_at ?? null
                        );
                    })->values();

                    $response['sets'][] = [
                        'set_id'   => $setId,          // <-- already exists, keep this
                        'set_name' => $set->name,      // <-- ADD THIS LINE to include set name
                        'today_total_count' => $exercisePayload->sum('today_count'),
                        'exercises' => $exercisePayload,
                    ];
                } else {
                    // Handle goals without set_id
                    $exercisePayload = $items->map(function ($item) use ($todayAssessments) {
                        $assessmentKey = ($item->set_id ?? 'null') . '_' . $item->exercise_id;
                        $todayAssessment = $todayAssessments->get($assessmentKey);
                        $todayCount = $todayAssessment ? (float) $todayAssessment->total_count : 0.0;

                        return $this->mapTodayExercisePayload(
                            $item,
                            null,
                            $todayCount,
                            $todayAssessment->last_logged_at ?? null
                        );
                    })->values();

                    $response['sets'][] = [
                        'set_id'   => $setId,        // <-- already exists
                        'set_name' => null,          // <-- ADD THIS LINE to keep set_name field consistent
                        'today_total_count' => $exercisePayload->sum('today_count'),
                        'exercises' => $exercisePayload,
                    ];
                }
            }


            return $this->success(
                $response,
                'Today workout fetched successfully',
                200
            );
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

            $day = $dayMap[$todayDay];

            $goals = Goal::where('user_id', $user->id)
                ->with(['exercise', 'set.setExercises' => function ($query) {
                    $query->orderBy('sequence');
                }])
                ->get()
                ->groupBy('set_id');

            $response = [
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email
                ],
                'date' => $todayDate,
                'day'  => $day,
                'sets' => []
            ];

            foreach ($goals as $setId => $items) {

                $rawDays = $items->first()->days ?? [];

                if (is_string($rawDays)) {
                    $decoded = json_decode($rawDays, true);
                    $setDays = is_array($decoded) ? $decoded : [];
                } elseif (is_array($rawDays)) {
                    $setDays = $rawDays;
                } else {
                    $setDays = [];
                }

                $setDays = collect($setDays)->unique()->values();

                if ($setId && $items->first()->set) {
                    $set = $items->first()->set;

                    $setExercises = $set->setExercises->pluck('sequence', 'exercise_id');

                    $sortedItems = $items->sortBy(function ($item) use ($setExercises) {
                        return $setExercises[$item->exercise_id] ?? 999;
                    });

                    $response['sets'][] = [
                        'set_id'   => $setId,
                        'set_name' => $set->name,
                        'days'     => $setDays,
                        'exercises' => $sortedItems->map(function ($item) use ($setExercises) {
                            return [
                                'goal_id'  => $item->id,
                                'value'    => $item->value,
                                'sequence' => $setExercises[$item->exercise_id] ?? null,
                                'exercise' => $item->exercise
                            ];
                        })->values()
                    ];
                } else {
                    $response['sets'][] = [
                        'set_id'   => $setId,
                        'set_name' => null,
                        'days'     => $setDays,
                        'exercises' => $items->map(function ($item) {
                            return [
                                'goal_id'  => $item->id,
                                'value'    => $item->value,
                                'sequence' => null,
                                'exercise' => $item->exercise
                            ];
                        })->values()
                    ];
                }
            }

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
