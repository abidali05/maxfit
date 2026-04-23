<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PlanQuestion;

class PlanQuestionController extends Controller
{
    public function __invoke()
    {
        $questions = PlanQuestion::all();
        return $this->success($questions, 'Questions fetched successfully', 200);
    }
}
