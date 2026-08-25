<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Country;
use App\Models\OrganisationTypes;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use App\Services\CompetitionEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\GroupInvitationMail;
use Brian2694\Toastr\Facades\Toastr;

class GroupController extends Controller
{
    protected $eligibilityService;

    public function __construct(CompetitionEligibilityService $eligibilityService)
    {
        $this->eligibilityService = $eligibilityService;
    }

    public function index()
    {
        $coachId = Auth::guard('coach')->id();
        $groups = Group::where('coach_id', $coachId)
            ->withCount('groupUsers')
            ->orderBy('id', 'desc')
            ->get();

        return view('coach.groups.index', compact('groups'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $organizationTypes = OrganisationTypes::orderBy('name')->get();
        $cities = \App\Models\City::orderBy('name')->get();

        return view('coach.groups.create', compact('countries', 'organizationTypes', 'cities'));
    }

    public function getEligibleUsers(Request $request)
    {
        try {
            $filters = [
                'age_group' => $request->input('age_group'),
                'country' => $request->input('country'),
                'org_types' => $request->input('org_types', []),
                'orgs' => $request->input('orgs', []),
                'genz' => $request->input('genz'),
                'gender' => $request->input('gender'),
            ];

            $users = $this->eligibilityService->query($filters)
                ->orderBy('name')
                ->get(['id', 'name', 'email']);

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'age_group' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'org_types' => 'nullable|array',
            'orgs' => 'nullable|array',
            'genz' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $group = Group::create([
                'coach_id' => Auth::guard('coach')->id(),
                'name' => $request->name,
                'instructions' => $request->instructions,
                'age_group' => $request->age_group,
                'gender' => $request->gender,
                'genz' => $request->genz,
                'country' => $request->country,
                'org_types' => $request->org_types,
                'orgs' => $request->orgs,
                'status' => 'active',
            ]);

            $userIds = $request->input('user_ids', []);
            foreach ($userIds as $userId) {
                GroupUser::create([
                    'group_id' => $group->id,
                    'user_id' => $userId,
                    'status' => 'pending',
                ]);

                // Send email to invited athlete
                $user = User::find($userId);
                if ($user && $user->email) {
                    try {
                        Mail::to($user->email)->send(new GroupInvitationMail($group, $user));
                    } catch (\Exception $e) {
                        Log::error("Failed to send group invite email to user {$userId}: " . $e->getMessage());
                    }
                }
            }

            DB::commit();
            Toastr::success('Athlete Group created successfully', 'Success');
            return redirect()->route('coach.groups.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create group: " . $e->getMessage());
            Toastr::error('An error occurred while creating group', 'Error');
            return back()->withInput();
        }
    }

    public function getOrganizationsByTypes(Request $request)
    {
        $orgTypeInput = $request->input('org_types', $request->input('org_type', []));
        $orgTypeIds = collect(is_array($orgTypeInput) ? $orgTypeInput : explode(',', (string) $orgTypeInput))
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();
        $term = trim((string) $request->input('term', ''));

        if ($orgTypeIds === []) {
            return response()->json(['results' => []]);
        }

        $organizations = \App\Models\Organisations::query()
            ->whereIn('type', $orgTypeIds)
            ->when($term !== '', function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'type'])
            ->map(fn ($organization) => [
                'id' => $organization->id,
                'name' => $organization->name,
                'text' => $organization->name,
                'type' => $organization->type,
            ])
            ->values();

        return response()->json(['results' => $organizations]);
    }

    public function show($id)
    {
        $coachId = Auth::guard('coach')->id();
        $group = Group::where('coach_id', $coachId)
            ->with(['groupUsers.user', 'coach', 'groupExercises.exercise', 'countryRelation'])
            ->findOrFail($id);

        $exercises = \App\Models\Exercise::orderBy('name')->get();

        return view('coach.groups.show', compact('group', 'exercises'));
    }

    public function edit($id)
    {
        $coachId = Auth::guard('coach')->id();
        $group = Group::where('coach_id', $coachId)
            ->with(['groupUsers.user'])
            ->findOrFail($id);

        $countries = Country::orderBy('name')->get();
        $organizationTypes = OrganisationTypes::orderBy('name')->get();
        $cities = \App\Models\City::orderBy('name')->get();

        $selectedUserIds = $group->groupUsers->pluck('user_id')->toArray();

        return view('coach.groups.edit', compact('group', 'countries', 'organizationTypes', 'cities', 'selectedUserIds'));
    }

    public function update(Request $request, $id)
    {
        $coachId = Auth::guard('coach')->id();
        $group = Group::where('coach_id', $coachId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'age_group' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'org_types' => 'nullable|array',
            'orgs' => 'nullable|array',
            'genz' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $group->update([
                'name' => $request->name,
                'instructions' => $request->instructions,
                'age_group' => $request->age_group,
                'gender' => $request->gender,
                'genz' => $request->genz,
                'country' => $request->country,
                'org_types' => $request->org_types,
                'orgs' => $request->orgs,
            ]);

            $selectedUserIds = collect($request->input('user_ids', []))->map(fn($v) => (int)$v)->all();

            $existingInvites = GroupUser::where('group_id', $group->id)->get();
            $existingUserIds = $existingInvites->pluck('user_id')->toArray();

            // 1. Remove users that are no longer selected
            GroupUser::where('group_id', $group->id)
                ->whereNotIn('user_id', $selectedUserIds)
                ->delete();

            // 2. Add newly selected users
            foreach ($selectedUserIds as $userId) {
                if (!in_array($userId, $existingUserIds)) {
                    GroupUser::create([
                        'group_id' => $group->id,
                        'user_id' => $userId,
                        'status' => 'pending',
                    ]);

                    // Send email to newly invited athlete
                    $user = User::find($userId);
                    if ($user && $user->email) {
                        try {
                            Mail::to($user->email)->send(new GroupInvitationMail($group, $user));
                        } catch (\Exception $e) {
                            Log::error("Failed to send group invite email to user {$userId}: " . $e->getMessage());
                        }
                    }
                }
            }

            DB::commit();
            Toastr::success('Athlete Group updated successfully', 'Success');
            return redirect()->route('coach.groups.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update group: " . $e->getMessage());
            Toastr::error('An error occurred while updating group', 'Error');
            return back()->withInput();
        }
    }

    public function assignExercises(Request $request, $id)
    {
        $coachId = Auth::guard('coach')->id();
        $group = Group::where('coach_id', $coachId)->findOrFail($id);

        $request->validate([
            'assignments' => 'nullable|array',
            'assignments.*.exercise_ids' => 'required|array|min:1',
            'assignments.*.exercise_ids.*' => 'required|exists:exercises,id',
            'assignments.*.start_date' => 'required|date',
            'assignments.*.end_date' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $group->groupExercises()->delete();

            $assignments = $request->input('assignments', []);
            foreach ($assignments as $assign) {
                $startDate = $assign['start_date'];
                $endDate = !empty($assign['end_date']) ? $assign['end_date'] : $startDate;
                if ($endDate < $startDate) {
                    $endDate = $startDate;
                }

                $exerciseIds = $assign['exercise_ids'] ?? [];
                foreach ($exerciseIds as $exerciseId) {
                    $group->groupExercises()->create([
                        'exercise_id' => $exerciseId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                }
            }

            DB::commit();
            Toastr::success('Exercise assignments updated successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to assign exercises: " . $e->getMessage());
            Toastr::error('An error occurred while saving exercises', 'Error');
            return redirect()->back()->withInput();
        }
    }
}
