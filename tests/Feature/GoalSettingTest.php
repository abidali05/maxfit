<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Set;
use App\Models\Exercise;
use App\Models\Goal;
use App\Models\ExerciseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class GoalSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_goal_with_exercise_specific_days()
    {
        $user = User::factory()->create();
        $set = Set::create([
            'name' => 'Strength Routine',
            'genz' => 'fatherfits',
            'fitness_level' => 'both',
            'gender' => 'both',
        ]);
        
        $cat = ExerciseCategory::create(['name' => 'Upper Body']);
        $ex1 = Exercise::create([
            'name' => 'Arm Circles',
            'exercise_category_id' => $cat->id,
            'exercise_type' => 'count',
            'genz' => 'fatherfits',
            'fitness_level' => 'both',
            'gender' => 'both',
        ]);
        $ex2 = Exercise::create([
            'name' => 'Pushups',
            'exercise_category_id' => $cat->id,
            'exercise_type' => 'count',
            'genz' => 'fatherfits',
            'fitness_level' => 'both',
            'gender' => 'both',
        ]);

        $payload = [
            'userId' => $user->id,
            'start_date' => '2026-08-27',
            'end_date' => '2026-09-27',
            'sets' => [
                [
                    'set_id' => $set->id,
                    'days' => ['M', 'W', 'F', 'T', 'Th'],
                    'exercises' => [
                        (string)$ex1->id => '10',
                        (string)$ex2->id => '15',
                    ],
                    'exercise_details' => [
                        [
                            'exercise_id' => $ex1->id,
                            'value' => '10',
                            'days' => ['M', 'W', 'F']
                        ],
                        [
                            'exercise_id' => $ex2->id,
                            'value' => '15',
                            'days' => ['T', 'Th']
                        ]
                    ]
                ]
            ]
        ];

        Sanctum::actingAs($user);
        $response = $this->postJson('/api/v1/store-goal-with-date', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Goals saved/updated set & day wise successfully'
            ]);

        $this->assertDatabaseHas('goals', [
            'user_id' => $user->id,
            'set_id' => $set->id,
            'exercise_id' => $ex1->id,
            'value' => '10',
            'days' => json_encode(['M', 'W', 'F']),
            'start_date' => '2026-08-27',
            'end_date' => '2026-09-27',
        ]);

        $this->assertDatabaseHas('goals', [
            'user_id' => $user->id,
            'set_id' => $set->id,
            'exercise_id' => $ex2->id,
            'value' => '15',
            'days' => json_encode(['T', 'Th']),
            'start_date' => '2026-08-27',
            'end_date' => '2026-09-27',
        ]);

        // Test getGoals returns each exercise with its own specific days
        $getGoalsRes = $this->getJson('/api/v1/get-goals');
        $getGoalsRes->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'start_date',
                    'end_date',
                    'sets' => [
                        '*' => [
                            'set_id',
                            'days',
                            'exercises',
                            'exercise_details' => [
                                '*' => ['exercise_id', 'value', 'days']
                            ],
                            'exercise_list' => [
                                '*' => ['goal_id', 'value', 'days', 'exercise']
                            ]
                        ]
                    ]
                ]
            ]);

        // Test getTodayGoals on Friday (F) returns ex1 (M,W,F) and NOT ex2 (T,Th)
        \Carbon\Carbon::setTestNow('2026-08-28'); // Friday
        $todayRes = $this->getJson('/api/v1/today-goals');
        $todayRes->assertStatus(200);
        $todayExercises = $todayRes->json('data.sets.0.exercises');
        $this->assertCount(1, $todayExercises);
        $this->assertEquals($ex1->id, $todayExercises[0]['exercise']['id']);

        // Test getTodayGoals on Tuesday (T) returns ex2 (T,Th) and NOT ex1 (M,W,F)
        \Carbon\Carbon::setTestNow('2026-09-01'); // Tuesday
        $tuesdayRes = $this->getJson('/api/v1/today-goals');
        $tuesdayRes->assertStatus(200);
        $tuesdayExercises = $tuesdayRes->json('data.sets.0.exercises');
        $this->assertCount(1, $tuesdayExercises);
        $this->assertEquals($ex2->id, $tuesdayExercises[0]['exercise']['id']);
    }
}
