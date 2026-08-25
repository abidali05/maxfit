<?php

namespace App\Repositories;

use App\Models\ExerciseCategory;
use App\Repositories\Contracts\ExerciseCategoryRepositoryInterface;

class ExerciseCategoryRepository implements ExerciseCategoryRepositoryInterface
{
    public function get_exercise_categories() {
        return ExerciseCategory::get();
    }

    public function get_exercise_category($id) {
        return ExerciseCategory::find($id);
    }

    public function create_exercise_category(array $data) {
        return ExerciseCategory::create($data);
    }

    public function update_exercise_category($id, array $data) {
        return ExerciseCategory::where('id', $id)->update($data);
    }

    public function delete_exercise_category($id) {
        return ExerciseCategory::where('id', $id)->delete();
    }
}
