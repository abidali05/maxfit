<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Country;
use App\Models\Exercise;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Organisations;
use App\Models\Venue;
use App\Models\CompetitionVideo;
use App\Models\CompetitionAppeal;
use App\Models\CompetitionDetail;
use App\Models\CompetitionResult;
use App\Models\OrganisationTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\CompetitionRepositoryInterface;
use App\Services\CompetitionEligibilityService;
use App\Services\AgeGroupParser;
use InvalidArgumentException;
use Illuminate\Support\Arr;

class CompetitionController extends Controller
{
    protected $competitionOption;
    protected CompetitionEligibilityService $eligibilityService;

    public function __construct(CompetitionRepositoryInterface $competitionOption, CompetitionEligibilityService $eligibilityService)
    {
        $this->competitionOption = $competitionOption;
        $this->eligibilityService = $eligibilityService;
    }

    public function index()
    {
        $competitions = $this->competitionOption->get_competitions();
        return view('competitions.index', compact('competitions'));
    }

    public function create()
    {
        $competition = new Competition();
        $countries = Country::orderBy('name')->get();
        $organizationTypes = OrganisationTypes::orderBy('name')->get();
        $coaches = Coach::orderBy('name')->get();
        $cities = \App\Models\City::orderBy('name')->get();
        $selectedOrgTypeIds = [];
        $selectedCountryId = null;
        $selectedCountryName = null;
        $selectedOrgIds = collect(Arr::wrap(old('org', [])))
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();
        $selectedOrgs = $selectedOrgIds === []
            ? []
            : Organisations::query()
                ->whereIn('id', $selectedOrgIds)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn ($org) => ['id' => $org->id, 'name' => $org->name])
                ->values()
                ->all();

        return view('competitions.create', compact(
            'competition',
            'countries',
            'organizationTypes',
            'coaches',
            'cities',
            'selectedOrgTypeIds',
            'selectedCountryId',
            'selectedCountryName',
            'selectedOrgIds',
            'selectedOrgs'
        ));
    }

    public function getOrganizationsByType($org_type_id)
    {
        $organizations = Organisations::where('type', $org_type_id)->get(['id', 'name']);
        return response()->json($organizations);
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

        $organizations = Organisations::query()
            ->whereIn('type', $orgTypeIds)
            ->when($term !== '', function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'type'])
            ->map(fn ($organization) => [
                'id' => $organization->id,
                'text' => $organization->name,
                'type' => $organization->type,
            ])
            ->values();

        return response()->json(['results' => $organizations]);
    }

    public function getVenuesByCity($cityId)
    {
        $venues = Venue::query()
            ->where('city_id', $cityId)
            ->orderBy('name')
            ->get(['id', 'name', 'city_id']);

        return response()->json($venues);
    }

    public function searchCountries(Request $request)
    {
        $term = trim((string) $request->input('term', ''));

        $countries = Country::query()
            ->when($term !== '', function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->limit(30)
            ->get(['id', 'name'])
            ->map(fn ($country) => [
                'id' => $country->id,
                'text' => $country->name,
            ])
            ->values();

        return response()->json(['results' => $countries]);
    }

    public function availableUsersCount(Request $request)
    {
        try {
            $orgInput = $request->input('orgs', $request->input('org', []));
            $orgIds = collect(is_array($orgInput) ? $orgInput : Arr::wrap($orgInput))
                ->filter()
                ->values()
                ->all();

            $filters = [
                'age_group' => $request->input('age_group'),
                'country' => $request->input('country'),
                'org_types' => $request->input('org_types', []),
                'orgs' => $orgIds,
                'genz' => $request->input('genz') ?: $this->eligibilityService->deriveGenzFromAgeGroup($request->input('age_group')),
            ];

            return response()->json([
                'count' => $this->eligibilityService->count($filters),
                'genz' => $filters['genz'],
            ]);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }

    private function validateCompetitionRequest(Request $request, bool $isUpdate = false): array
    {
        $orgInput = $request->input('org', []);
        $request->merge([
            'org' => array_values(array_filter(Arr::wrap($orgInput), fn ($value) => $value !== null && $value !== '')),
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'age_group' => ['required', 'string', 'regex:/^\d+\s*(?:-\s*\d+)?$/'],
            'genz' => 'required|in:motherfits,fatherfits',
            'country' => 'required|exists:countries,id',
            'description' => 'nullable|string',
            'org_type' => 'nullable|array',
            'org_type.*' => 'required|exists:organisation_types,id',
            'org' => 'nullable|array',
            'org.*' => 'required|exists:organisations,id',
            'time_allowed' => 'required|numeric|min:1',
            'competition_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'youtube_links' => 'nullable|array',
            'youtube_links.*' => 'nullable|url',
            'has_entry_fee' => 'required|boolean',
            'entry_fee' => 'nullable|numeric|min:0|required_if:has_entry_fee,1',
            'coach_id' => 'required|array|min:1',
            'coach_id.*' => 'required|exists:coaches,id',
            'city_id' => 'required|array|min:1',
            'city_id.*' => 'required|exists:cities,id',
            'venue_id' => 'required|array|min:1',
            'venue_id.*' => 'required|exists:venues,id',
            'start_date' => 'required|array|min:1',
            'start_date.*' => 'required|date',
            'end_date' => 'required|array|min:1',
            'end_date.*' => 'required|date',
            'start_time' => 'required|array|min:1',
            'start_time.*' => 'required|date_format:H:i',
            'end_time' => 'required|array|min:1',
            'end_time.*' => 'required|date_format:H:i',
            'image' => 'nullable|array',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($isUpdate) {
            $rules['status'] = 'nullable|in:active,inactive';
            $rules['detail_ids'] = 'nullable|array';
            $rules['detail_ids.*'] = 'nullable|integer|exists:competition_details,id';
        }

        $validated = $request->validate($rules);

        $parsedAgeGroup = AgeGroupParser::parse($validated['age_group']);
        $validated['age_group_min'] = $parsedAgeGroup['min'];
        $validated['age_group_max'] = $parsedAgeGroup['max'];
        $validated['derived_genz'] = $this->eligibilityService->deriveGenzFromAgeGroup($validated['age_group']);

        if (!empty($validated['derived_genz']) && $validated['genz'] !== $validated['derived_genz']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'genz' => ['Genz does not match the selected age group.'],
            ]);
        }

        if (count($validated['coach_id']) !== count($validated['city_id']) ||
            count($validated['coach_id']) !== count($validated['venue_id']) ||
            count($validated['coach_id']) !== count($validated['start_date']) ||
            count($validated['coach_id']) !== count($validated['end_date']) ||
            count($validated['coach_id']) !== count($validated['start_time']) ||
            count($validated['coach_id']) !== count($validated['end_time'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'coach_id' => ['Each competition detail field must have matching rows.'],
            ]);
        }

        foreach ($validated['start_date'] as $index => $startDate) {
            if (!empty($validated['end_date'][$index]) && strtotime($validated['end_date'][$index]) < strtotime($startDate)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'end_date.' . $index => ['End date must be on or after start date.'],
                ]);
            }

            if (
                !empty($validated['start_time'][$index]) &&
                !empty($validated['end_time'][$index]) &&
                $validated['start_date'][$index] === ($validated['end_date'][$index] ?? null) &&
                strtotime($validated['end_time'][$index]) <= strtotime($validated['start_time'][$index])
            ) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'end_time.' . $index => ['End time must be after start time.'],
                ]);
            }

            $venueId = $validated['venue_id'][$index] ?? null;
            if (!empty($venueId)) {
                $venue = Venue::find($venueId);
                if ($venue && (string) $venue->city_id !== (string) ($validated['city_id'][$index] ?? '')) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'venue_id.' . $index => ['Selected venue must belong to the selected city.'],
                    ]);
                }
            }
        }

        $selectedOrgIds = array_values(array_filter(array_map('intval', $validated['org'] ?? [])));
        if ($selectedOrgIds !== []) {
            $organizations = Organisations::query()
                ->whereIn('id', $selectedOrgIds)
                ->get(['id', 'type']);

            if ($organizations->count() !== count(array_unique($selectedOrgIds))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'org' => ['Selected organization is invalid.'],
                ]);
            }

            $selectedOrgTypes = array_map('intval', $validated['org_type'] ?? []);
            if ($selectedOrgTypes !== []) {
                foreach ($organizations as $organization) {
                    if (!in_array((int) $organization->type, $selectedOrgTypes, true)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'org' => ['Selected organization must belong to the selected organization type(s).'],
                        ]);
                    }
                }
            }
        }

        return $validated;
    }

    private function syncCompetitionOrganisations(Competition $competition, array $validated): void
    {
        $orgTypeIds = array_values(array_unique(array_filter(array_map('intval', $validated['org_type'] ?? []))));
        if (Schema::hasTable('competition_organisation_types')) {
            $competition->organizationTypes()->sync($orgTypeIds);
        }
        $competition->org_type = $orgTypeIds[0] ?? null;

        $orgIds = array_values(array_unique(array_filter(array_map('intval', $validated['org'] ?? []))));
        if (Schema::hasTable('competition_organisations')) {
            $competition->organisations()->sync($orgIds);
        }
        $competition->org = $orgIds[0] ?? null;
    }

    private function syncCompetitionVideos(Competition $competition, Request $request, array $validated): void
    {
        $competition->videos()->delete();

        foreach (($validated['youtube_links'] ?? []) as $link) {
            if ($link) {
                CompetitionVideo::create([
                    'competition_id' => $competition->id,
                    'video_file' => $link,
                ]);
            }
        }
    }

    private function syncCompetitionDetails(Competition $competition, Request $request, array $validated): void
    {
        $existingIds = $competition->details()->pluck('id')->all();
        $submittedIds = array_filter($validated['detail_ids'] ?? [], fn ($id) => !empty($id));
        $idsToDelete = array_diff($existingIds, $submittedIds);
        $uploadedImages = $request->file('image', []);

        foreach ($idsToDelete as $detailId) {
            $detail = CompetitionDetail::find($detailId);
            if ($detail && $detail->image) {
                Storage::disk('public')->delete($detail->image);
            }
            $detail?->delete();
        }

        foreach ($validated['coach_id'] as $index => $coachId) {
            $city = \App\Models\City::find($validated['city_id'][$index]);
            $detailData = [
                'competition_id' => $competition->id,
                'coach_id' => $coachId,
                'city_id' => !empty($validated['city_id'][$index]) ? (int) $validated['city_id'][$index] : null,
                'venue_id' => !empty($validated['venue_id'][$index]) ? (int) $validated['venue_id'][$index] : null,
                'city' => $city?->name,
                'start_date' => $validated['start_date'][$index],
                'end_date' => $validated['end_date'][$index],
                'start_time' => $validated['start_time'][$index],
                'end_time' => $validated['end_time'][$index],
            ];

            if (!empty($uploadedImages[$index])) {
                $detailData['image'] = $uploadedImages[$index]->store('competitionDetails', 'public');
            }

            if (!empty($validated['detail_ids'][$index])) {
                $existing = CompetitionDetail::find($validated['detail_ids'][$index]);
                if ($existing && isset($detailData['image']) && $existing->image) {
                    Storage::disk('public')->delete($existing->image);
                }
                CompetitionDetail::whereKey($validated['detail_ids'][$index])->update($detailData);
            } else {
                CompetitionDetail::create($detailData);
            }
        }
    }


    public function store(Request $request)
    {
        $validated = $this->validateCompetitionRequest($request);

        DB::beginTransaction();
        try {
            $competition = Competition::create([
                'name' => $validated['name'],
                'age_group' => $validated['age_group'],
                'genz' => $validated['genz'],
                'country' => $validated['country'],
                'description' => $validated['description'] ?? null,
                'time_allowed' => $validated['time_allowed'],
                'org_type' => !empty($validated['org_type']) ? $validated['org_type'][0] : null,
                'org' => !empty($validated['org']) ? $validated['org'][0] : null,
                'has_entry_fee' => $validated['has_entry_fee'],
                'entry_fee' => $validated['has_entry_fee'] ? ($validated['entry_fee'] ?? null) : null,
                'status' => 'active',
            ]);

            if ($request->hasFile('competition_image')) {
                $file = $request->file('competition_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/competitions'), $filename);
                $competition->competition_image = 'uploads/competitions/' . $filename;
                $competition->save();
            }

            $this->syncCompetitionOrganisations($competition, $validated);
            $competition->save();
            $this->syncCompetitionDetails($competition, $request, $validated);
            $this->syncCompetitionVideos($competition, $request, $validated);

            $exerciseIds = Exercise::whereIn('genz', [$validated['genz'], 'both'])->pluck('id');
            $competition->exercises()->sync($exerciseIds);

            DB::commit();
            Toastr::success('Competition and details created successfully', 'Success');
            return redirect()->route('competitions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Failed to create competition: ' . $e->getMessage(), 'Error');
            return back()->withErrors(['error' => 'Failed to create competition.'])->withInput();
        }
    }


    public function edit($id)
    {
        try {
            $with = [
                'details.cityRelation',
                'details.venueRelation',
                'videos',
                'organizationTypes',
                'exercises',
            ];
            if (Schema::hasTable('competition_organisations')) {
                $with[] = 'organisations';
            }

            $competition = Competition::with($with)->findOrFail($id);

            $countries = Country::orderBy('name')->get();
            $organizationTypes = OrganisationTypes::orderBy('name')->get();
            $selectedOrgTypeIds = $competition->organizationTypes->pluck('id')->all();
            if ($selectedOrgTypeIds === [] && !empty($competition->org_type)) {
                $selectedOrgTypeIds = [(int) $competition->org_type];
            }
            $selectedCountryId = is_numeric($competition->country)
                ? (int) $competition->country
                : Country::query()->where('name', $competition->country)->value('id');
            $selectedCountryName = $selectedCountryId
                ? Country::query()->where('id', $selectedCountryId)->value('name')
                : (is_string($competition->country) ? $competition->country : null);
            $coaches = Coach::orderBy('name')->get();
            $cities = \App\Models\City::orderBy('name')->get();

            $selectedOrgIds = collect(Arr::wrap(old('org', [])))
                ->filter()
                ->map(fn ($value) => (int) $value)
                ->values()
                ->all();
            $selectedOrgs = $selectedOrgIds === []
                ? []
                : Organisations::query()
                    ->whereIn('id', $selectedOrgIds)
                    ->orderBy('name')
                    ->get(['id', 'name'])
                    ->map(fn ($org) => ['id' => $org->id, 'name' => $org->name])
                    ->values()
                    ->all();

            if ($selectedOrgIds === []) {
                if (Schema::hasTable('competition_organisations') && $competition->relationLoaded('organisations')) {
                    $selectedOrgIds = $competition->organisations->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
                    $selectedOrgs = $competition->organisations
                        ->map(fn ($org) => ['id' => $org->id, 'name' => $org->name])
                        ->values()
                        ->all();
                } elseif (!empty($competition->org) && $competition->organisation) {
                    $selectedOrgIds = [(int) $competition->org];
                    $selectedOrgs = [['id' => $competition->organisation->id, 'name' => $competition->organisation->name]];
                }
            }

            return view('competitions.edit', compact(
                'competition',
                'countries',
                'organizationTypes',
                'coaches',
                'cities',
                'selectedOrgTypeIds',
                'selectedCountryId',
                'selectedCountryName',
                'selectedOrgIds',
                'selectedOrgs'
            ));
        } catch (\Exception $e) {
            Toastr::error('Failed to load form data: ' . $e->getMessage(), 'Error');
            return back();
        }
    }

    public function show(string $id)
    {
        $competitionDetail = CompetitionDetail::where('competition_id', $id)
            ->with('coach')
            ->get();

        return view('competitions.view', compact('competitionDetail', 'id'));
    }



    public function storeResults(Request $request, string $id)
    {
        $competition = $this->competitionOption->view_competition($id);
        $exercises = Exercise::where('genz', $competition->genz)->pluck('id')->toArray();

        $validated = $request->validate([
            'results' => 'required|array',
            'results.*.competition_user_id' => 'required|exists:competition_users,id',
            'results.*.exercise_id' => 'required|in:' . implode(',', $exercises),
            'results.*.score' => 'required|numeric|min:0',
        ]);

        foreach ($validated['results'] as $result) {
            CompetitionResult::updateOrCreate(
                [
                    'competition_user_id' => $result['competition_user_id'],
                    'exercise_id' => $result['exercise_id'],
                ],
                [
                    'score' => $result['score'],
                ]
            );
        }

        Toastr::success('Results saved successfully', 'Success');
        return redirect()->route('competitions.show', $id);
    }

    public function update(Request $request, string $id)
    {
        $validated = $this->validateCompetitionRequest($request, true);

        DB::beginTransaction();
        try {
            $competition = Competition::with('details', 'videos')->findOrFail($id);

            if ($request->hasFile('competition_image')) {
                if ($competition->competition_image && Storage::disk('public')->exists($competition->competition_image)) {
                    Storage::disk('public')->delete($competition->competition_image);
                }

                $file = $request->file('competition_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/competitions'), $filename);
                $competition->competition_image = 'uploads/competitions/' . $filename;
            }

            $competition->fill([
                'name' => $validated['name'],
                'age_group' => $validated['age_group'],
                'genz' => $validated['genz'],
                'country' => $validated['country'],
                'description' => $validated['description'] ?? null,
                'time_allowed' => $validated['time_allowed'],
                'org_type' => !empty($validated['org_type']) ? $validated['org_type'][0] : null,
                'org' => !empty($validated['org']) ? $validated['org'][0] : null,
                'status' => $validated['status'] ?? ($competition->status ?? 'active'),
                'has_entry_fee' => $validated['has_entry_fee'],
                'entry_fee' => $validated['has_entry_fee'] ? ($validated['entry_fee'] ?? null) : null,
            ]);
            $competition->save();

            $this->syncCompetitionOrganisations($competition, $validated);
            $competition->save();
            $this->syncCompetitionDetails($competition, $request, $validated);
            $this->syncCompetitionVideos($competition, $request, $validated);

            $exerciseIds = Exercise::whereIn('genz', [$validated['genz'], 'both'])->pluck('id');
            $competition->exercises()->sync($exerciseIds);

            DB::commit();
            Toastr::success('Competition and details updated successfully', 'Success');
            return redirect()->route('competitions.index');
        } catch (QueryException $e) {
            DB::rollBack();
            Toastr::error('Failed to update competition: ' . $e->getMessage(), 'Error');
            Log::error('Competition update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update competition.'])->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Failed to update competition: ' . $e->getMessage(), 'Error');
            Log::error('Competition update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update competition.'])->withInput();
        }
    }


    public function destroy(string $id)
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
        return redirect()->route('competitions.index');
    }

    public function appeals()
    {
        $appeals = CompetitionAppeal::with('competitionVideo.competition', 'user')
            ->get();
        return view('competitions.appeals', compact('appeals'));
    }

    public function appealDetails(string $id)
    {
        $competition = $this->competitionOption->get_competition($id);
        return view('competitions.appeal_details', compact('competition'));
    }

    public function competitionVideos()
    {
        $videos = CompetitionVideo::all();
        return view('competitions.videos', compact('videos'));
    }

    public function competitionAppeals()
    {
        $appeals = CompetitionAppeal::all();
        return view('competitions.appeals', compact('appeals'));
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
