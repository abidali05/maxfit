<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\API\AuthRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Challenge;
use App\Models\ChallengeExercise;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;

class ChallengesController extends Controller
{
    /**
     * Get all challenges
     */
    public function index()
    {
        $challenges = Challenge::with(['challenger', 'challengeTo', 'challengeExercises.exercise', 'challengeExercises.set'])->get();

        return view('Challenges.index', compact('challenges'));
    }
    public function show_web($id)
    {
        $challenge = Challenge::with(['challenger', 'challengeTo', 'challengeExercises.exercise', 'challengeExercises.set'])->find($id);
        return view('Challenges.show', compact('challenge'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'challenge_to_id' => 'required|exists:users,id',

            // ✅ venue_type only 2 options
            'venue_type' => 'required|in:self,third_party_physical',

            // ✅ venue_id required only when third_party_physical
            'venue_id' => 'nullable|exists:venues,id|required_if:venue_type,third_party_physical',

            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'exercises' => 'required|array',
            'start_time' => 'nullable',
            'end_time' => 'nullable',

            'exercises.*.set_id' => 'required|exists:sets,id',
            'exercises.*.exercise_id' => 'required|array',
            'exercises.*.exercise_id.*' => 'required|exists:exercises,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // ✅ If self => venue_id force null (save na ho)
        $venueIdToSave = ($data['venue_type'] === 'third_party_physical')
            ? $data['venue_id']
            : null;

        $challenge = Challenge::create([
            'challenger_id' => auth()->id(),
            'challenge_to_id' => $data['challenge_to_id'],
            'venue_type' => $data['venue_type'],      // ✅ recommend save this too
            'venue_id' => $venueIdToSave,             // ✅ conditional save
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'start_time' => $data['start_time'] ?? null,
            'end_time' => $data['end_time'] ?? null,
        ]);

        foreach ($request->exercises as $row) {
            $set_id = $row['set_id'];
            $exercise_ids = $row['exercise_id'];

            foreach ($exercise_ids as $exercise_id) {
                ChallengeExercise::create([
                    'challenge_id' => $challenge->id,
                    'exercise_id' => $exercise_id,
                    'set_id' => $set_id,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Challenge created successfully',
            'data' => $challenge->load('challengeExercises.exercise', 'challengeExercises.set')
        ], 201);
    }


    // Challenges created by logged-in user
    public function challenges_i_make()
    {
        $user = Auth::user();

        $challenges = Challenge::with(['challengeTo', 'challengeExercises.exercise', 'challengeExercises.set'])
            ->where('challenger_id', $user->id)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Challenges you made fetched successfully',
            'data' => $challenges
        ], 200);
    }

    // Challenges assigned to logged-in user
    public function challenges_i_get()
    {
        $user = Auth::user();

        // $challenges = Challenge::with(['challenger', 'challengeExercises.exercise', 'challengeExercises.set'])
        //                 ->where('challenge_to_id', $user->id)
        //                 ->get();


        $challenges_me = Challenge::with([
            'challenger',
            'challengeExercises.exercise',
            'challengeExercises.set'
        ])
            ->where('challenge_to_id', $user->id)
            ->get()
            ->map(function ($item) {
                $item->challenge_status = false;
                return $item;
            });

        $challenges_get = Challenge::with([
            'challengeTo',
            'challengeExercises.exercise',
            'challengeExercises.set'
        ])
            ->where('challenger_id', $user->id)
            ->get()
            ->map(function ($item) {
                $item->challenge_status = true;
                return $item;
            });

        // ✔ Merge both collections
        $challenges = $challenges_me->merge($challenges_get);


        return response()->json([
            'status' => true,
            'message' => 'Challenges assigned to you fetched successfully',
            'data' => $challenges
        ], 200);
    }


    public function updateStatus(Request $request, $id)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,accepted,rejected',
            'venue_id' => 'nullable|exists:venues,id',
            'rejection_reason' => 'required_if:status,rejected|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find the challenge
        $challenge = Challenge::find($id);

        if (!$challenge) {
            return response()->json([
                'success' => false,
                'message' => 'Challenge not found',
            ], 404);
        }

        // Update status
        $challenge->status = $request->status;

        // Save rejection reason if status is rejected
        if ($request->status === 'rejected') {
            $challenge->rejection_reason = $request->rejection_reason;
        } else {
            $challenge->rejection_reason = null;
            $challenge->venue_id = $request->venue_id;
        }

        $challenge->save();

        return response()->json([
            'success' => true,
            'message' => 'Challenge status updated successfully',
            'challenge' => $challenge
        ], 200);
    }


    /**
     * Show a specific challenge
     */
    public function show($id)
    {
        $challenge = Challenge::with(['challenger', 'challengeTo', 'challengeExercises.exercise', 'challengeExercises.set'])->find($id);

        if (!$challenge) {
            return response()->json([
                'status' => false,
                'message' => 'Challenge not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Challenge fetched successfully',
            'data' => $challenge
        ], 200);
    }

    /**
     * Delete a challenge
     */
    public function destroy($id)
    {
        $challenge = Challenge::find($id);

        if (!$challenge) {
            return response()->json([
                'status' => false,
                'message' => 'Challenge not found'
            ], 404);
        }

        $challenge->delete();

        return response()->json([
            'status' => true,
            'message' => 'Challenge deleted successfully'
        ], 200);
    }

    public function userResultGraph()
    {
        $user = Auth::user();

        // 1. Get Latest Competition for auth user
        $userCompetition = \App\Models\CompetitionUser::where('user_id', $user->id)
            ->with('competitionDetail.competition')
            ->orderBy('created_at', 'desc')
            ->first();

        $userGoalValue = Goal::where('user_id', $user->id)->sum('value');

        $userTotalScore = 0;
        $latestCompetition = null;
        $winner = null;

        if ($userCompetition && $userCompetition->competitionDetail) {

            $latestCompetition = $userCompetition->competitionDetail->competition;

            $userTotal = \App\Models\CompetitionUserTotal::where(
                'competition_user_id',
                $userCompetition->id
            )->first();

            $userTotalScore = $userTotal ? $userTotal->total_score : 0;

            // 4. Winner
            $winner = \App\Models\CompetitionUserTotal::with(['competitionUser'])
                ->whereHas('competitionUser.competitionDetail', function ($query) use ($latestCompetition) {
                    $query->where('competition_id', $latestCompetition->id);
                })
                ->orderBy('total_score', 'desc')
                ->first();
        }

        if ((int)$userTotalScore === 0) {
            $assessmentSum = \App\Models\UserExerciseAssessment::where('user_id', $user->id)
                ->sum('value');

            $userTotalScore = (int) $assessmentSum;
        }

        return response()->json([
            'status' => true,
            'message' => 'User result graph data fetched successfully',
            'data' => [
                'latest_competition' => $latestCompetition ? [
                    'id' => $latestCompetition->id,
                    'name' => $latestCompetition->name,
                    'age_group' => $latestCompetition->age_group,
                    'genz' => $latestCompetition->genz,
                    'country' => $latestCompetition->country,
                    'time_allowed' => $latestCompetition->time_allowed,
                    'competition_image' => $latestCompetition->competition_image,
                    'has_entry_fee' => $latestCompetition->has_entry_fee,
                    'entry_fee' => $latestCompetition->entry_fee
                ] : null,

                'user_total_score' => $userTotalScore,
                'winner' => $winner ? [
                    'user_id' => $winner->competitionUser->user_id,
                    'total_score' => $winner->total_score,
                    'rank' => $winner->rank
                ] : null,

                'user_goal_value' => $userGoalValue
            ]
        ], 200);
    }
}
