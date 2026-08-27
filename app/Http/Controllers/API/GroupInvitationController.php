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

    public function getGroupDetails(Request $request, $id)
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

        // Get target date (default to today)
        $targetDate = $request->query('date', now()->toDateString());
        try {
            $dateObj = \Carbon\Carbon::parse($targetDate);
            $targetDate = $dateObj->toDateString();
            $dayName = $dateObj->format('l'); // e.g. "Friday"
        } catch (\Exception $e) {
            $targetDate = now()->toDateString();
            $dayName = now()->format('l');
        }

        // Get active exercises assigned to this group for the target date
        $groupExercises = GroupExercise::where('group_id', $id)
            ->where('start_date', '<=', $targetDate)
            ->where('end_date', '>=', $targetDate)
            ->where(function ($q) use ($targetDate, $dayName) {
                $q->where('day', $targetDate)
                  ->orWhere('day', $dayName)
                  ->orWhere('day', 'Everyday')
                  ->orWhereNull('day');
            })
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->with(['exercise.exercise_category'])
            ->get();

        // Get target date's submissions for this athlete and group
        $submissions = GroupExerciseSubmission::where('group_id', $id)
            ->where('user_id', $userId)
            ->where('submitted_date', $targetDate)
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
                'order' => (int)$ge->order,
                'assigned_day' => $ge->day,
                'schedule_start_date' => $ge->start_date ? \Carbon\Carbon::parse($ge->start_date)->toDateString() : null,
                'schedule_end_date' => $ge->end_date ? \Carbon\Carbon::parse($ge->end_date)->toDateString() : null,
            ];
        })->filter()->values();

        // Build complete day-wise routines breakdown for the entire group schedule
        $allGroupExercises = GroupExercise::where('group_id', $id)
            ->with(['exercise.exercise_category'])
            ->orderBy('start_date', 'asc')
            ->orderBy('day', 'asc')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $allAthleteSubmissions = GroupExerciseSubmission::where('group_id', $id)
            ->where('user_id', $userId)
            ->get();

        $todayDateStr = now()->toDateString();
        $todayDayStr = now()->format('l');

        $routinesGrouped = $allGroupExercises->groupBy(function ($item) {
            return $item->start_date . '_' . $item->end_date . '_' . ($item->day ?? 'Everyday');
        });

        $routinesData = [];
        foreach ($routinesGrouped as $routineKey => $items) {
            $firstItem = $items->first();
            $dayValue = $firstItem->day ?? 'Everyday';
            
            $isSpecificDate = false;
            $routineDate = null;
            $routineDayName = $dayValue;
            $formattedLabel = $dayValue;

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dayValue)) {
                $isSpecificDate = true;
                $routineDate = $dayValue;
                $cDate = \Carbon\Carbon::parse($dayValue);
                $routineDayName = $cDate->format('l');
                $formattedLabel = $cDate->format('l (M d, Y)');
            } elseif ($dayValue === 'Everyday') {
                $formattedLabel = 'Everyday';
            } else {
                $formattedLabel = 'Every ' . $dayValue . ' (Recurring)';
            }

            $isToday = false;
            if ($isSpecificDate) {
                $isToday = ($routineDate === $todayDateStr);
            } elseif ($dayValue === 'Everyday') {
                $isToday = true;
            } elseif ($dayValue === $todayDayStr) {
                $isToday = true;
            }

            $isSelected = ($routineDate && $routineDate === $targetDate) || ($isToday && $targetDate === $todayDateStr);

            $routineSubmissionsCount = 0;
            $routineExercises = $items->map(function ($ge) use ($allAthleteSubmissions, $routineDate, $todayDateStr, &$routineSubmissionsCount) {
                $exercise = $ge->exercise;
                if (!$exercise) return null;

                $checkDate = $routineDate ?? $todayDateStr;
                $sub = $allAthleteSubmissions->first(function ($s) use ($exercise, $checkDate) {
                    return (int)$s->exercise_id === (int)$exercise->id && $s->submitted_date === $checkDate;
                });

                $isSubmitted = !is_null($sub);
                if ($isSubmitted) {
                    $routineSubmissionsCount++;
                }

                $rawType = strtolower($exercise->exercise_type ?? 'count');
                $isSec = str_contains($rawType, 'sec');
                $exerciseType = $isSec ? 'per second' : 'per count';
                $unit = $isSec ? 'per sec' : 'per count';

                return [
                    'id' => $exercise->id,
                    'name' => $exercise->name,
                    'category' => $exercise->exercise_category->name ?? 'N/A',
                    'image' => $exercise->image ? url('storage/' . $exercise->image) : asset('assets/images/user.jpg'),
                    'count' => $isSubmitted ? (int)$sub->count : 0,
                    'description' => $exercise->description ?? '',
                    'youtubeLink' => $exercise->youtube_link ?? '',
                    'is_submitted' => $isSubmitted,
                    'exercise_type' => $exerciseType,
                    'unit' => $unit,
                    'order' => (int)$ge->order,
                ];
            })->filter()->values();

            $routinesData[] = [
                'day' => $formattedLabel,
                'day_name' => $routineDayName,
                'date' => $routineDate,
                'is_specific_date' => $isSpecificDate,
                'is_today' => $isToday,
                'is_selected' => $isSelected,
                'schedule_start_date' => $firstItem->start_date ? \Carbon\Carbon::parse($firstItem->start_date)->toDateString() : null,
                'schedule_end_date' => $firstItem->end_date ? \Carbon\Carbon::parse($firstItem->end_date)->toDateString() : null,
                'total_exercises_count' => $routineExercises->count(),
                'submitted_exercises_count' => $routineSubmissionsCount,
                'exercises' => $routineExercises,
            ];
        }

        $data = [
            'group_id' => $group->id,
            'group_name' => $group->name,
            'coach_name' => $group->coach->name ?? 'N/A',
            'athletes_count' => $group->groupUsers->count() . ' athletes',
            'status' => $invite->status === 'accepted' ? 'active' : $invite->status,
            'group_status' => $group->status ?? 'active',
            'instructions' => $group->instructions ?? '',
            'today_date' => $todayDateStr,
            'today_day' => $todayDayStr,
            'selected_date' => $targetDate,
            'day_name' => $dayName,
            'total_exercises_count' => $totalExercisesCount,
            'submitted_exercises_count' => $submittedExercisesCount,
            'exercises' => $exercisesData,
            'routines' => $routinesData,
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
