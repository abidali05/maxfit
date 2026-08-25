<?php

namespace App\Repositories\Contracts;

interface ExerciseRepositoryInterface
{
    public function get_exercises();
    public function get_exercise_caetegories();
    public function get_exercise($id);
    public function create_exercise(array $data);
    public function update_exercise($id, array $data);
    public function delete_exercise($id);
}
