<?php

namespace App\Repositories;

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Repositories\Contracts\ExerciseRepositoryInterface;

class ExerciseRepository implements ExerciseRepositoryInterface
{
    public function get_exercises() {
        return Exercise::get();
    }

    public function get_exercise_caetegories() {
        return ExerciseCategory::get();
    }

    public function get_exercise($id) {
        return Exercise::find($id);
    }

    public function create_exercise(array $data) {
        return Exercise::create($data);
    }

    public function update_exercise($id, array $data) {
        return Exercise::where('id', $id)->update($data);
    }

    public function delete_exercise($id) {
        return Exercise::where('id', $id)->delete();
    }
}
