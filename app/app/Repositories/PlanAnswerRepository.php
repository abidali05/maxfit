<?php

namespace App\Repositories;

use App\Models\PlanAnswer;
use App\Repositories\Contracts\PlanAnswerRepositoryInterface;

class PlanAnswerRepository implements PlanAnswerRepositoryInterface
{
    public function store_plan_answers(array $data)
    {
        return PlanAnswer::create($data);
    }
}
