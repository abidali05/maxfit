<?php

namespace App\Repositories;

use App\Models\MedicalAssessmentQuestion;
use App\Repositories\Contracts\MedicalAssessmentQuestionRepositoryInterface;

class MedicalAssessmentQuestionRepository implements MedicalAssessmentQuestionRepositoryInterface
{

    public function get_medical_assessment_questions () {
        return MedicalAssessmentQuestion::get();
    }

    public function store_medical_assessment_question(array $data) {
        return MedicalAssessmentQuestion::create($data);
    }

    public function get_medical_assessment_question($id) {
        return MedicalAssessmentQuestion::find($id);
    }

    public function update_medical_assessment_question($id, array $data) {
        return MedicalAssessmentQuestion::where('id', $id)->update($data);
    }

    public function delete_medical_assessment_question($id) {
        return MedicalAssessmentQuestion::where('id', $id)->delete();
    }
}
