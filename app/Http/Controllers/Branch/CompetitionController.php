<?php

namespace App\Http\Controllers\Branch;

use App\Models\Coach;
use App\Models\Exercise;
use App\Models\Competition;
use Illuminate\Http\Request;
use App\Models\Organisations;
use App\Models\CompetitionUser;
use App\Models\CompetitionVideo;
use App\Models\CompetitionAppeal;
use App\Models\CompetitionDetail;
use App\Models\CompetitionResult;
use App\Models\OrganisationTypes;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CompetitionUserTotal;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\CompetitionRepositoryInterface;

class CompetitionController extends Controller
{
    protected $competitionOption;

    public function __construct(CompetitionRepositoryInterface $competitionOption)
    {
        $this->competitionOption = $competitionOption;
    }

    public function getCompetitions()
    {
        $competitions = $this->competitionOption->get_branch_competitions();
        return view('branch.competition.index', compact('competitions'));
    }

    public function createCompetitions()
    {
        $organizations = Organisations::all();
        $organizationTypes = OrganisationTypes::all();
        $coaches = Coach::all();

        return view('branch.competition.create', compact('organizations', 'organizationTypes', 'coaches'));
    }

    public function getOrganizationsByType($org_type_id)
    {
        $organizations = Organisations::where('type', $org_type_id)->get(['id', 'name']);
        return response()->json($organizations);
    }


    public function storeCompetitions(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age_group' => 'required|numeric|min:1',
            'genz' => 'required|in:motherfits,fatherfits',
            'country' => 'required|string|max:100',
            'org_type' => 'nullable|exists:organisation_types,id',
            'org' => 'nullable|exists:organisations,id',
            'time_allowed' => 'nullable|numeric|min:1',
            'coach_id' => 'required|array|min:1',
            'coach_id.*' => 'required|string|max:100',
            'cities' => 'required|array|min:1',
            'cities.*' => 'required|string|max:100',
            'start_date' => 'nullable|array',
            'start_date.*' => 'nullable|date',
            'end_date' => 'nullable|array',
            'end_date.*' => 'nullable|date',
            'start_time' => 'nullable|array',
            'start_time.*' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|array',
            'end_time.*' => 'nullable|date_format:H:i',
            'image' => 'nullable|array',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
        ]);

        // Custom validation for end_date and end_time
        foreach ($validated['cities'] as $index => $city) {
            if (
                isset($validated['start_date'][$index], $validated['end_date'][$index]) &&
                $validated['start_date'][$index] && $validated['end_date'][$index] &&
                strtotime($validated['end_date'][$index]) < strtotime($validated['start_date'][$index])
            ) {
                return back()->withErrors(['end_date.' . $index => 'End date must be on or after start date.']);
            }
            if (
                isset($validated['start_date'][$index], $validated['end_date'][$index], $validated['start_time'][$index], $validated['end_time'][$index]) &&
                $validated['start_date'][$index] == $validated['end_date'][$index] &&
                $validated['start_time'][$index] && $validated['end_time'][$index] &&
                strtotime($validated['end_time'][$index]) <= strtotime($validated['start_time'][$index])
            ) {
                return back()->withErrors(['end_time.' . $index => 'End time must be after start time.']);
            }
        }

        // Begin database transaction
        DB::beginTransaction();
        try {
            // Create a single competition record
            $competitionData = [
                'user_id' => Auth::guard('branch')->user()->id,
                'name' => $validated['name'],
                'age_group' => $validated['age_group'],
                'genz' => $validated['genz'],
                'country' => $validated['country'],
                'time_allowed' => $validated['time_allowed'] ?? null,
                'org_type' => $validated['org_type'] ?? null,
                'org' => $validated['org'] ?? null,
                'status' => 'active',
            ];

            $competition = Competition::create($competitionData);

            // Create competition details for each set
            foreach ($validated['coach_id'] as $index => $coach_id) {
                $detailData = [
                    'competition_id' => $competition->id,
                    'coach_id' => $coach_id,
                    'city' => $validated['cities'][$index] ?? null,
                    'start_date' => $validated['start_date'][$index] ?? null,
                    'end_date' => $validated['end_date'][$index] ?? null,
                    'start_time' => $validated['start_time'][$index] ?? null,
                    'end_time' => $validated['end_time'][$index] ?? null,
                    'description' => $validated['description'][$index] ?? null,
                ];

                // Handle image upload
                if (isset($request->file('image')[$index]) && $request->file('image')[$index]) {
                    $detailData['image'] = $request->file('image')[$index]->store('competitionDetails', 'public');
                }

                CompetitionDetail::create($detailData);

                // Sync exercises with competition
                $exerciseIds = Exercise::whereIn('genz', [$validated['genz'], 'both'])
                    ->pluck('id');

                // Attach exercises once (not inside the foreach)
                $competition->exercises()->sync($exerciseIds);
            }

            DB::commit();
            Toastr::success('Competition and details created successfully', 'Success');
            return redirect()->route('branch.getCompetitions');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Failed to create competition: ' . $e->getMessage(), 'Error');
            return back()->withErrors(['error' => 'Failed to create competition.']);
        }
    }

    public function showCompetition(string $id)
    {
        $competitionDetail = CompetitionDetail::where('competition_id', $id)->with('coach')->get();
        return view('branch.competition.view', compact('competitionDetail', 'id'));
    }

    public function editCompetition(string $id)
    {
        $organizations = Organisations::all();
        $organizationTypes = OrganisationTypes::all();

        $competition = $this->competitionOption->get_competition($id);
        return view('branch.competition.edit', compact('competition', 'organizations', 'organizationTypes'));
    }

    public function updateCompetition(Request $request, $id)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'age_group'     => 'required|string|max:255',
            'country'       => 'required|string|max:255',
            'time_allowed'  => 'nullable|integer|min:1',
            'status'        => 'nullable|in:active,inactive',
            'org_type' => 'nullable|exists:organisation_types,id', // Validate
            'org' => 'nullable|exists:organisations,id', // Validate
            'youtube_links.*' => 'nullable|url'
        ]);

        $youtubeLinks = $request->youtube_links ?? [];
        unset($validated['youtube_links']);

        $this->competitionOption->update_competition($id, $validated, $youtubeLinks);

        Toastr::success('Competition updated successfully', 'Success');
        return redirect()->route('branch.getCompetitions');
    }


    public function deleteCompetition(string $id)
    {
        $competition = $this->competitionOption->get_competition($id);

        // Delete image
        if ($competition->image && Storage::disk('public')->exists($competition->image)) {
            Storage::disk('public')->delete($competition->image);
        }

        // Delete videos (assuming a Competition hasMany videos relationship)
        foreach ($competition->videos as $video) {
            if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }
            $video->delete();
        }

        // Delete competition itself
        $this->competitionOption->delete_competition($id);

        Toastr::success('Competition deleted successfully', 'Success');
        return redirect()->route('branch.getCompetitions');
    }

    public function getCompetitionDetail()
    {
        $competitionDetails = CompetitionDetail::get();
        return view('coach.competition-list', compact('competitionDetails'));
    }

    public function getCompetitionDetailUser($id)
    {
        $competition = CompetitionDetail::with('competitionUsers.user')->findOrFail($id);

        return view('coach.competition-list-user', compact('competition'));
    }

    public function getCompetitionDetailUserUpdate($id)
    {
        $competitionUser = CompetitionUser::with(['user', 'competitionDetail.competition'])->findOrFail($id);

        // Get all exercises for this competition
        $competition = $competitionUser->competitionDetail->competition;
        $exercises = $competition->exercises; // via competition_exercises

        // Get existing scores
        $results = CompetitionResult::where('competition_user_id', $competitionUser->id)->get()->keyBy('exercise_id');

        return view('branch.competition-users-edit', compact('competitionUser', 'exercises', 'results'));
    }

    public function getCompetitionResultUpdate(Request $request, $id)
    {
        $competitionUser = CompetitionUser::findOrFail($id);

        $scores = $request->input('scores'); // array: exercise_id => score

        $totalScore = 0;

        foreach ($scores as $exerciseId => $score) {
            CompetitionResult::updateOrCreate(
                [
                    'competition_user_id' => $competitionUser->id,
                    'exercise_id' => $exerciseId
                ],
                ['score' => $score]
            );

            $totalScore += floatval($score);
        }

        // Save or update total score
        CompetitionUserTotal::updateOrCreate(
            ['competition_user_id' => $competitionUser->id],
            ['total_score' => $totalScore]
        );

        return redirect()->back()->with('success', 'Scores updated!');
    }

    public function editCompetitionResultUpdate($id)
    {
        $competitionDetail = CompetitionDetail::findOrFail($id);
        $coaches = Coach::all();

        return view('branch.competition.competition-users.edit', compact('competitionDetail', 'coaches'));
    }

    public function updateCompetitionResultUpdate(Request $request, $id)
    {
        $CompetitionDetail = CompetitionDetail::findOrFail($id);
        $validated = $request->validate([
            'coach_id' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('competitionDetails', 'public');
        }

        $competitionId = $request->competition_id;

        $CompetitionDetail->update($validated);
        return redirect()->back()->with('success', 'Competition detail updated successfully.');
    }

    public function competitionVideos()
    {
        $authId = Auth::guard('branch')->user()->id;

        $videos = CompetitionVideo::whereHas('competition', function ($query) use ($authId) {
            $query->where('user_id', $authId);
        })->get();

        return view('branch.competition.videos', compact('videos'));
    }

    public function competitionAppeals()
    {
        $authId = Auth::id();

        $appeals = CompetitionAppeal::whereHas('competitionVideo.competition', function ($query) use ($authId) {
            $query->where('user_id', $authId);
        })->get();

        return view('branch.competition.appeals', compact('appeals'));
    }

    public function destroyAppeal($id)
    {
        $appeal = CompetitionAppeal::findOrFail($id);
        $appeal->delete();
        Toastr::success('Appeal deleted successfully', 'Success');
        return redirect()->back();
    }

    public function updateAppealStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        $appeal = CompetitionAppeal::findOrFail($id);
        $appeal->status = $request->status;
        $appeal->save();
        Toastr::success('Appeal status updated successfully', 'Success');
        return redirect()->back();
    }
}
