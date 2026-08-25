<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ExerciseGroup;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseGroupController extends Controller
{
    public function getPopularExerciseGroups(Request $request)
    {
        $allGroups = ExerciseGroup::where('status', 'active')
            ->orderBy('id', 'asc')
            ->get();

        if ($allGroups->isEmpty()) {
            return $this->success([
                'groups' => [],
                'selected_group' => null,
                'exercises' => [
                    'current_page' => 1,
                    'data' => [],
                    'total' => 0,
                    'per_page' => 15,
                    'last_page' => 1,
                ]
            ], 'No exercise groups available');
        }

        // Determine selected group: requested group_id or default to 1st group
        $requestedGroupId = $request->input('group_id');
        $selectedGroup = $requestedGroupId 
            ? $allGroups->firstWhere('id', (int)$requestedGroupId) 
            : $allGroups->first();

        if (!$selectedGroup) {
            $selectedGroup = $allGroups->first();
        }

        $perPage = (int) $request->input('per_page', 15);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        // Paginate exercises belonging to the selected group
        $exercisesPaginator = $selectedGroup->exercises()
            ->with('exercise_category')
            ->paginate($perPage);

        // Transform groups list for horizontal tabs
        $groupsData = $allGroups->map(function ($grp) use ($selectedGroup) {
            return [
                'id' => $grp->id,
                'name' => $grp->name,
                'sub_title' => $grp->sub_title ?? 'Popular by Sport',
                'image' => $grp->image ? url('storage/' . $grp->image) : null,
                'icon' => $grp->icon ?? 'fa-dumbbell',
                'is_selected' => ($grp->id === $selectedGroup->id),
            ];
        });

        // Transform paginated exercises
        $exercisesData = $exercisesPaginator->getCollection()->map(function ($exercise, $index) use ($exercisesPaginator) {
            // Determine duration / reps label
            $durationOrReps = $exercise->video_time ?: null;
            if (!$durationOrReps) {
                if ($exercise->exercise_type === 'sec' || $exercise->exercise_type === 'seconds') {
                    $durationOrReps = '30 sec';
                } else {
                    $durationOrReps = '12 reps';
                }
            }

            return [
                'item_number' => (($exercisesPaginator->currentPage() - 1) * $exercisesPaginator->perPage()) + $index + 1,
                'id' => $exercise->id,
                'name' => $exercise->name,
                'category' => $exercise->exercise_category->name ?? 'Exercise',
                'description' => $exercise->description ?? '',
                'fitness_level' => $exercise->fitness_level ?? 'All Levels',
                'exercise_type' => $exercise->exercise_type ?? 'count',
                'duration_or_reps' => $durationOrReps,
                'image' => $exercise->image ? url('storage/' . $exercise->image) : asset('assets/images/user.jpg'),
                'youtube_link' => $exercise->youtube_link ?? null,
            ];
        });

        $paginatedResponse = [
            'current_page' => $exercisesPaginator->currentPage(),
            'data' => $exercisesData,
            'first_page_url' => $exercisesPaginator->url(1),
            'from' => $exercisesPaginator->firstItem(),
            'last_page' => $exercisesPaginator->lastPage(),
            'last_page_url' => $exercisesPaginator->url($exercisesPaginator->lastPage()),
            'next_page_url' => $exercisesPaginator->nextPageUrl(),
            'path' => $exercisesPaginator->path(),
            'per_page' => $exercisesPaginator->perPage(),
            'prev_page_url' => $exercisesPaginator->previousPageUrl(),
            'to' => $exercisesPaginator->lastItem(),
            'total' => $exercisesPaginator->total(),
        ];

        return $this->success([
            'groups' => $groupsData,
            'selected_group' => [
                'id' => $selectedGroup->id,
                'name' => $selectedGroup->name,
                'sub_title' => $selectedGroup->sub_title ?? 'Popular by Sport',
                'image' => $selectedGroup->image ? url('storage/' . $selectedGroup->image) : null,
                'icon' => $selectedGroup->icon ?? 'fa-dumbbell',
                'total_items' => $exercisesPaginator->total(),
            ],
            'exercises' => $paginatedResponse,
        ], 'Popular exercises fetched successfully');
    }
}
