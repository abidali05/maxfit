<?php

namespace App\Repositories\Contracts;

interface ExerciseCategoryRepositoryInterface
{
    public function get_exercise_categories();
    public function get_exercise_category($id);
    public function create_exercise_category(array $data);
    public function update_exercise_category($id, array $data);
    public function delete_exercise_category($id);
}
