<?php

namespace App\Repositories;

use App\Models\PlanQuestion;
use App\Repositories\Contracts\PlanQuestionRepositoryInterface;

class PlanQuestionRepository implements PlanQuestionRepositoryInterface
{
    public function get_plan_questions () {
        return PlanQuestion::get();
    }

    public function store_plan_question(array $data) {
        return PlanQuestion::create($data);
    }

    public function get_plan_question($id) {
        return PlanQuestion::find($id);
    }

    public function update_plan_question($id, array $data) {
        return PlanQuestion::where('id', $id)->update($data);
    }

    public function delete_plan_question($id) {
        return PlanQuestion::where('id', $id)->delete();
    }
}
