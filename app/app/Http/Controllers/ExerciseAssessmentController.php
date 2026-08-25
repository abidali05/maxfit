<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Models\Exercise;
use App\Models\UserExerciseAssessment;
use Illuminate\Http\Request;

class ExerciseAssessmentController extends Controller
{
    public function index()
    {
        $sets = Set::with('exercises')->get();
        return view('exercise_assessment.index', compact('sets'));
    }

    public function setDetails($setId)
    {
        $set = Set::with('exercises')->findOrFail($setId);
        return view('exercise_assessment.set_details', compact('set'));
    }

    public function exerciseDetails($exerciseId)
    {
        $exercise = Exercise::findOrFail($exerciseId);

        $assessments = UserExerciseAssessment::with('user')
            ->where('exercise_id', $exerciseId)
            ->get()
            ->groupBy('user_id');

        return view('exercise_assessment.exercise_details', compact('exercise', 'assessments'));
    }
}
