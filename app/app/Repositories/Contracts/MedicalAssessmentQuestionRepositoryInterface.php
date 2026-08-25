<?php
namespace App\Repositories\Contracts;
interface MedicalAssessmentQuestionRepositoryInterface {
    public function get_medical_assessment_questions();
    public function store_medical_assessment_question(array $data);
    public function get_medical_assessment_question($id);
    public function update_medical_assessment_question($id, array $data);
    public function delete_medical_assessment_question($id);
}
?>
