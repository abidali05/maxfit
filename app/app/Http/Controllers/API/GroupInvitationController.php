<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\GroupExercise;
use App\Models\GroupExerciseSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupInvitationController extends Controller
{

    public function getInvitations()
    {
        $userId = Auth::id();

        // Get pending and accepted invitations
        $invitations = GroupUser::where('user_id', $userId)
            ->whereIn('status', ['pending', 'accepted'])
            ->with(['group.coach', 'group.groupUsers'])
            ->get();

        $pendingCount = $invitations
            ->where('status', 'pending')
            ->count();

        // Only accepted + group active should be counted as active
        $activeCount = $invitations
            ->filter(function ($invite) {
                return $invite->status === 'accepted'
                    && ($invite->group->status ?? 'active') === 'active';
            })
            ->count();

        // Suspended groups count
        $suspendedCount = $invitations
            ->filter(function ($invite) {
                return $invite->status === 'accepted'
                    && ($invite->group->status ?? 'active') === 'suspended';
            })
            ->count();

        $groups = $invitations->map(function ($invite) {

            $created = $invite->created_at;

            if ($created->isToday()) {
                $requestedDate = 'Today';
            } elseif ($created->isYesterday()) {
                $requestedDate = 'Yesterday';
            } else {
                $requestedDate = $created->diffForHumans();
            }

            $groupStatus = $invite->group->status ?? 'active';

            return [
                'group_id' => $invite->group_id,
                'group_name' => $invite->group->name ?? 'Group',
                'coach_name' => $invite->group->coach->name ?? 'Coach',
                'athletes_count' => ($invite->group->groupUsers->count() ?? 0) . ' members',
                'requested_date' => $requestedDate,

                // If group is suspended, show suspended
                'status' => $groupStatus === 'suspended'
                    ? 'suspended'
                    : ($invite->status === 'accepted' ? 'active' : $invite->status),

                'group_status' => $groupStatus,
            ];
        });

        return $this->success([
            'pending_count' => $pendingCount,
            'active_count' => $activeCount,
            'suspended_count' => $suspendedCount,
            'groups' => $groups
        ], 'Group invitations fetched successfully');
    }

    public function respondToInvitation(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'status' => 'required|in:accepted,rejected',
        ]);

        $userId = Auth::id();
        $groupId = $request->input('group_id');
        $status = $request->input('status');

        $invite = GroupUser::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();

        if (!$invite) {
            return $this->notFound('Group invitation not found');
        }

        $invite->update([
            'status' => $status,
        ]);

        return $this->success([], 'Group invitation ' . $status . ' successfully');
    }

    public function getGroupDetails($id)
    {
        $userId = Auth::id();

        // Verify the user is part of the group
        $invite = GroupUser::where('group_id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$invite) {
            return $this->notFound('Group not found or you are not a member');
        }

        $group = Group::with(['coach', 'groupUsers'])->findOrFail($id);

        // Get active exercises assigned to this group for today
        $today = now()->toDateString();
        $groupExercises = GroupExercise::where('group_id', $id)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->with(['exercise.exercise_category'])
            ->get();

        // Get today's submissions for this athlete and group
        $submissions = GroupExerciseSubmission::where('group_id', $id)
            ->where('user_id', $userId)
            ->where('submitted_date', $today)
            ->get()
            ->keyBy('exercise_id');

        $totalExercisesCount = $groupExercises->count();
        $submittedExercisesCount = 0;

        $exercisesData = $groupExercises->map(function ($ge) use ($submissions, &$submittedExercisesCount) {
            $exercise = $ge->exercise;
            if (!$exercise) return null;

            $submission = $submissions->get($exercise->id);
            $isSubmitted = !is_null($submission);

            if ($isSubmitted) {
                $submittedExercisesCount++;
            }

            $exerciseType = $exercise->exercise_type ?? 'count';
            $unit = ($exerciseType === 'sec' || $exerciseType === 'seconds') ? 'per sec' : 'per count';

            return [
                'id' => $exercise->id,
                'name' => $exercise->name,
                'category' => $exercise->exercise_category->name ?? 'N/A',
                'image' => $exercise->image ? url('storage/' . $exercise->image) : asset('assets/images/user.jpg'),
                'count' => $isSubmitted ? (int)$submission->count : 0,
                'description' => $exercise->description ?? '',
                'youtubeLink' => $exercise->youtube_link ?? '',
                'is_submitted' => $isSubmitted,
                'exercise_type' => $exerciseType,
                'unit' => $unit,
            ];
        })->filter()->values();

        $data = [
            'group_id' => $group->id,
            'group_name' => $group->name,
            'coach_name' => $group->coach->name ?? 'N/A',
            'athletes_count' => $group->groupUsers->count() . ' athletes',
            'status' => $invite->status === 'accepted' ? 'active' : $invite->status,
            'group_status' => $group->status ?? 'active',
            'instructions' => $group->instructions ?? '',
            'total_exercises_count' => $totalExercisesCount,
            'submitted_exercises_count' => $submittedExercisesCount,
            'exercises' => $exercisesData,
        ];

        return $this->success($data, 'Group details fetched successfully');
    }

    public function submitExercises(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'submissions' => 'required|array|min:1',
            'submissions.*.exercise_id' => 'required|exists:exercises,id',
            'submissions.*.count' => 'required|integer|min:0',
        ]);

        $userId = Auth::id();
        $groupId = $request->input('group_id');
        $today = now()->toDateString();

        // Verify membership
        $invite = GroupUser::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first();

        if (!$invite) {
            return $this->notFound('Group not found or you are not a member');
        }

        DB::beginTransaction();
        try {
            foreach ($request->input('submissions', []) as $sub) {
                GroupExerciseSubmission::updateOrCreate([
                    'group_id' => $groupId,
                    'user_id' => $userId,
                    'exercise_id' => $sub['exercise_id'],
                    'submitted_date' => $today,
                ], [
                    'count' => $sub['count'],
                ]);
            }

            DB::commit();
            return $this->success([], 'Group exercises submitted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during submission: ' . $e->getMessage()
            ], 500);
        }
    }
}
