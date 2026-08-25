<?php

namespace App\Repositories\Contracts;

interface PlanQuestionRepositoryInterface
{
    public function get_plan_questions();
    public function store_plan_question(array $data);
    public function get_plan_question($id);
    public function update_plan_question($id, array $data);
    public function delete_plan_question($id);
}
