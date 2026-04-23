<?php

namespace App\Repositories\Contracts;

interface PlanRepositoryInterface
{
    public function get_plans();
    public function store_plan(array $data);
    public function get_plan($id);
    public function update_plan($id, array $data);
    public function delete_plan($id);
}
