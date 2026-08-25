<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\MedicalAssessmentQuestion;
use App\Repositories\Contracts\API\MedicalAssessmentAnswerInterface;

class MedicalAssessmentAnswerController extends Controller
{
    protected $answer;

    public function __construct(MedicalAssessmentAnswerInterface $answer)
    {
        $this->answer = $answer;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // $user = auth('sanctum')->user();
        Log::info($request->all());
        try {
            $validated = $request->validate([
                'answers' => 'required|string',
            ]);

            $answers = json_decode($validated['answers'], true);

            // Check if JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->error('Invalid JSON format for answers.', [], 422);
            }

            // Validate the decoded array
            $questionIds = array_keys($answers);

            // Check if all question IDs are valid
            $validQuestionIds = MedicalAssessmentQuestion::whereIn('id', $questionIds)->pluck('id')->toArray();
            $invalidIds = array_diff($questionIds, $validQuestionIds);

            if (!empty($invalidIds)) {
                return $this->error("Invalid question IDs: " . implode(', ', $invalidIds), [], 422);
            }

            // Process the valid answers and store them
            $userId = $request->user_id;
            $storedAnswers = [];
            foreach ($answers as $questionId => $answer) {
                // Store each answer (assuming store_medical_assessment_answers method exists)
                $storedAnswers[] = $this->answer->store_medical_assessment_answers([
                    'question_id' => $questionId,
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
