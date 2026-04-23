<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Set;
use App\Models\Exercise;
use App\Models\User;
use App\Models\ExerciseCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

    public function assesmentExercises()
    {
        $user = Auth::user();

        $age = Carbon::parse($user->dob)->age;

        if ($age < 14) {
            $allowedGenz = ['motherfits', 'both'];
        } else {
            $allowedGenz = ['fatherfits', 'both'];
        }

        $sets = Set::with('exercises')->whereIn('genz', $allowedGenz)
            ->get();

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
