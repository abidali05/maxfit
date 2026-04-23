<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserAssessmentExerciseController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|exists:users,id',
            'exercise' => 'required|array',
            'exercise.*' => 'nullable|numeric',
            'skip' => 'nullable|in:yes'
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors()->toArray(), 'Validation Error');
        }

        DB::beginTransaction();

        try {
            $userId = $request->userId;
            $exerciseData = $request->input('exercise');
            $skip = $request->skip === 'yes';

            $insertData = [];

            foreach ($exerciseData as $exerciseId => $value) {
                $insertData[] = [
                    'user_id' => $userId,
                    'exercise_id' => $exerciseId,
                    'value' => $value,
                    'is_skip' => $skip ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('user_exercise_assessment')->insert($insertData);

            if (!$skip) {
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'assessment' => true,
                        'profile_step' => "4",
                    ]);
            } else {
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'is_skip' => $skip ? 1 : 0,
                    ]);
            }

            DB::commit();

            return $this->success(null, 'User Exercise Assessments added successfully', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), [], 500);
        }
    }
}
