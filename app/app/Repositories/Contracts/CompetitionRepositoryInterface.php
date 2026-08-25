<?php

namespace App\Repositories\Contracts;

interface CompetitionRepositoryInterface
{
    public function get_competitions();
    public function get_branch_competitions();
    public function store_competition(array $data);
    public function get_competition($id);
    public function view_competition($id);
    public function update_competition($id, array $data);
    public function delete_competition($id);
}
