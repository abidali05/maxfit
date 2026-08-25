<?php

namespace App\Http\Controllers\API;

use App\Models\PlanQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PlanAnswerRepositoryInterface;

class PlanAnswerController extends Controller
{
    protected $answer;

    public function __construct(PlanAnswerRepositoryInterface $answer)
    {
        $this->answer = $answer;
    }

    public function __invoke(Request $request)
    {
        // $user = auth('sanctum')->user();
        Log::info($request->all());
        try {
            $validated = $request->validate([
                'answers' => 'required|array',
            ]);

            $answers = $validated['answers'];

            // Check if JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->error('Invalid JSON format for answers.', [], 422);
            }

            // Validate the decoded array
            $questionIds = array_keys($answers);

            // Check if all question IDs are valid
            $validQuestionIds = PlanQuestion::whereIn('id', $questionIds)->pluck('id')->toArray();
            $invalidIds = array_diff($questionIds, $validQuestionIds);

            if (!empty($invalidIds)) {
                return $this->error("Invalid question IDs: " . implode(', ', $invalidIds), [], 422);
            }

            // Process the valid answers and store them
            $userId = $request->user_id;
            $storedAnswers = [];
            foreach ($answers as $questionId => $answer) {
                // Store each answer (assuming store_medical_assessment_answers method exists)
                $storedAnswers[] = $this->answer->store_plan_answers([
                    'plan_question_id' => $questionId,
                    'answer' => $answer,
                    'user_id' => $userId,
                ]);
            }

            return $this->success($storedAnswers, 'Medical assessment added successfully', 200);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), [], 422);
        }
    }
}
