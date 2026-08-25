<?php

namespace App\Repositories\API;

use App\Models\MedicalAssessmentAnswer;
use App\Repositories\Contracts\API\MedicalAssessmentAnswerInterface;

class MedicalAssessmentAnswerRepository implements MedicalAssessmentAnswerInterface
{
    public function store_medical_assessment_answers(array $data)
    {
        return MedicalAssessmentAnswer::create($data);
    }
}
