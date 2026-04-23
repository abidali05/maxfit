<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MedicalAssessmentQuestion;

class MedicalAssessmentQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $questions = MedicalAssessmentQuestion::all()->groupBy('assessment_type');
        return $this->success($questions, 'Questions fetched successfully', 200);
    }
}
