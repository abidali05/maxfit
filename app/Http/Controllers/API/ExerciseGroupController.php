<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ExerciseGroup;
use App\Models\ExerciseSubGroup;
use Illuminate\Http\Request;

class ExerciseGroupController extends Controller
{
    public function getPopularExerciseGroups(Request $request)
    {
        $allGroups = ExerciseGroup::where('status', 'active')
            ->with(['subGroups' => function ($q) {
                $q->where('status', 'active')->orderBy('order')->orderBy('id', 'asc');
            }])
            ->orderBy('id', 'asc')
            ->get();

        if ($allGroups->isEmpty()) {
            return $this->success([
                'groups' => [],
                'selected_group' => null,
                'sub_groups' => [],
                'selected_sub_group' => null,
                'exercises' => [
                    'current_page' => 1,
                    'data' => [],
                    'total' => 0,
                    'per_page' => 15,
                    'last_page' => 1,
                ]
            ], 'No exercise groups available');
        }

        // 1. Determine selected group: requested group_id or default to 1st group
        $requestedGroupId = $request->input('group_id');
        $selectedGroup = $requestedGroupId 
            ? $allGroups->firstWhere('id', (int)$requestedGroupId) 
            : $allGroups->first();

        if (!$selectedGroup) {
            $selectedGroup = $allGroups->first();
        }

        // 2. Determine Sub-Groups for the selected group
        $subGroups = $selectedGroup->subGroups ?? collect();

        // 3. Determine selected sub-group: requested sub_group_id or default to 1st sub-group of selected group
        $requestedSubGroupId = $request->input('sub_group_id');
        $selectedSubGroup = null;

        if ($subGroups->isNotEmpty()) {
            $selectedSubGroup = $requestedSubGroupId
                ? $subGroups->firstWhere('id', (int)$requestedSubGroupId)
                : $subGroups->first();

            if (!$selectedSubGroup) {
                $selectedSubGroup = $subGroups->first();
            }
        }

        // 4. Paginate exercises of the selected sub-group
        $perPage = (int)$request->input('per_page', 15);
        $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 15;

        if ($selectedSubGroup) {
            $paginatedExercises = $selectedSubGroup->exercises()
                ->with(['exercise_category'])
                ->paginate($perPage);

            $exercisesCollection = $paginatedExercises->getCollection();
            $currentPage = $paginatedExercises->currentPage();
            $startingNumber = ($currentPage - 1) * $perPage + 1;

            $transformedExercises = $exercisesCollection->map(function ($exercise, $index) use ($startingNumber) {
                $exerciseType = $exercise->exercise_type ?? 'count';
                $videoTime = $exercise->video_time ?? ($exerciseType === 'sec' ? '30 sec' : '10 reps');

                return [
                    'item_number' => $startingNumber + $index,
                    'id' => $exercise->id,
                    'name' => $exercise->name,
                    'category' => $exercise->exercise_category->name ?? 'N/A',
                    'description' => $exercise->description ?? '',
                    'fitness_level' => $exercise->fitness_level ?? 'N/A',
                    'exercise_type' => $exerciseType,
                    'duration_or_reps' => $videoTime,
                    'image' => $exercise->image ? url('storage/' . $exercise->image) : asset('assets/images/user.jpg'),
                    'youtube_link' => $exercise->youtube_link ?? null,
                ];
            });

            $paginatedExercises->setCollection($transformedExercises);
            $exercisesData = $paginatedExercises;
        } else {
            $exercisesData = [
                'current_page' => 1,
                'data' => [],
                'total' => 0,
                'per_page' => $perPage,
                'last_page' => 1,
            ];
        }

        // Format Groups list for UI tabs
        $groupsData = $allGroups->map(function ($grp) use ($selectedGroup) {
            return [
                'id' => $grp->id,
                'name' => $grp->name,
                'sub_title' => $grp->sub_title ?? '',
                'image' => $grp->image ? url('storage/' . $grp->image) : null,
                'icon' => $grp->icon ?? 'fa-dumbbell',
                'is_selected' => $grp->id === $selectedGroup->id,
            ];
        });

        // Format Sub-Groups list for UI sub-tabs
        $subGroupsData = $subGroups->map(function ($sg) use ($selectedSubGroup) {
            return [
                'id' => $sg->id,
                'name' => $sg->name,
                'sub_title' => $sg->sub_title ?? '',
                'image' => $sg->image ? url('storage/' . $sg->image) : null,
                'is_selected' => $selectedSubGroup && ($sg->id === $selectedSubGroup->id),
            ];
        });

        $responseData = [
            'groups' => $groupsData,
            'selected_group' => [
                'id' => $selectedGroup->id,
                'name' => $selectedGroup->name,
                'sub_title' => $selectedGroup->sub_title ?? '',
                'image' => $selectedGroup->image ? url('storage/' . $selectedGroup->image) : null,
                'icon' => $selectedGroup->icon ?? 'fa-dumbbell',
                'total_sub_groups' => $subGroups->count(),
            ],
            'sub_groups' => $subGroupsData,
            'selected_sub_group' => $selectedSubGroup ? [
                'id' => $selectedSubGroup->id,
                'name' => $selectedSubGroup->name,
                'sub_title' => $selectedSubGroup->sub_title ?? '',
                'image' => $selectedSubGroup->image ? url('storage/' . $selectedSubGroup->image) : null,
                'total_items' => $selectedSubGroup->exercises()->count(),
            ] : null,
            'exercises' => $exercisesData,
        ];

        return $this->success($responseData, 'Popular exercises fetched successfully');
    }
}
