<?php

namespace App\Repositories;

use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;

class PlanRepository implements PlanRepositoryInterface
{
    public function get_plans () {
        return Plan::get();
    }

    public function store_plan(array $data) {
        return Plan::create($data);
    }

    public function get_plan($id) {
        return Plan::find($id);
    }

    public function update_plan($id, array $data) {
        return Plan::where('id', $id)->update($data);
    }

    public function delete_plan($id) {
        return Plan::where('id', $id)->delete();
    }
}
