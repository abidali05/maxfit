<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\Set;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExercisesController extends Controller
{
    public function __invoke()
    {
        $exercises = Exercise::get();
        return $this->success($exercises, 'Exercises fetched successfully', 200);
    }

    public function getCategory()
    {
        $category = ExerciseCategory::latest()->get();
        return $this->success($category, 'Category fetched successfully', 200);
    }

    public function getCategoryExercises($id)
    {
        $category = ExerciseCategory::findOrFail($id);
        $exercises = Exercise::where('exercise_category_id', $id)->latest()->get();

        return $this->success([
            'category' => $category,
            'exercises' => $exercises
        ], 'Exercises fetched successfully', 200);
    }

    private function normalizeGender(?string $gender): ?string
    {
        $value = strtolower(trim((string) $gender));

        return match ($value) {
            'male' => 'Male',
            'female' => 'Female',
            'both' => 'both',
            default => null,
        };
    }

    private function normalizeFitnessLevel(?string $value): ?string
    {
        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            'expert' => 'Expert',
            'amateur' => 'Amateur',
            'both' => 'both',
            default => null,
        };
    }

    public function assesmentExercises()
    {
        $user = Auth::user();

        $age = Carbon::parse($user->dob)->age;
        $genz = $age < 14 ? 'motherfits' : 'fatherfits';

        $latestAssessment = $user->latestPhysicalAssessment()->first();
        $fitnessLevel = $this->normalizeFitnessLevel($latestAssessment?->exercise_type);
        $gender = $this->normalizeGender($user->gender ?: $latestAssessment?->gender);

        $userAssessments = DB::table('daily_assessments')
            ->where('user_id', $user->id)
            ->pluck('count', 'exercise_id');

        $sets = Set::query()
            ->with('exercises')
            ->matchingCriteria($genz, $fitnessLevel ?? 'both', $gender ?? 'both')
            ->get()
            ->map(function ($set) use ($userAssessments) {
                $set->exercises->map(function ($exercise) use ($userAssessments) {
                    $exercise->user_value = $userAssessments->get($exercise->id);
                    return $exercise;
                });
                return $set;
            });

        return $this->success($sets, 'Sets fetched successfully', 200);
    }

    public function getAllUsers()
    {
        return $this->success('same category users fetched successfully', 200);
        $loggedInUser = Auth::user();

        $users = User::get();

        $loggedAge = Carbon::parse($loggedInUser->dob)->age;

        $allowedGenz = ($loggedAge < 14)
            ? ['motherfits', 'both']
            : ['fatherfits', 'both'];

        $loggedInUser->genz = $allowedGenz;

        foreach ($users as $u) {
            $age = Carbon::parse($u->dob)->age;

            $u->genz = ($age < 14)
                ? ['motherfits', 'both']
                : ['fatherfits', 'both'];
        }

        $final_users = $users->filter(function ($u) use ($allowedGenz) {
            return !empty(array_intersect($u->genz, $allowedGenz));
        });

        return $this->success($final_users->values(), 'same category users fetched successfully', 200);
    }
}
