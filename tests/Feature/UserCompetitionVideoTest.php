<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    DB::statement('PRAGMA foreign_keys = OFF;');

    Schema::table('users', function ($table) {
        if (!Schema::hasColumn('users', 'role')) $table->string('role')->default('user');
    });

    Schema::table('competition_users', function ($table) {
        if (!Schema::hasColumn('competition_users', 'competition_detail_id')) {
            $table->unsignedBigInteger('competition_detail_id')->nullable();
        }
    });

    // Seed required database tables for validation constraints
    if (!DB::table('exercise_categories')->where('id', 1)->exists()) {
        DB::table('exercise_categories')->insert([
            'id' => 1,
            'name' => 'Strength',
            'tag' => 'strength',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    if (!DB::table('exercises')->where('id', 1)->exists()) {
        DB::table('exercises')->insert([
            'id' => 1,
            'exercise_category_id' => 1,
            'name' => 'Pushups',
            'genz' => 'fatherfits',
            'image' => 'exercise_images/pushup.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
});

test('it returns user competition videos', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'image' => 'profile.jpg',
    ]);

    // Insert mock competition_users, competition_results, competition_result_videos
    $compId = DB::table('competitions')->insertGetId([
        'name' => 'Test Competition',
        'age_group' => 15,
        'country' => 'PK',
        'genz' => 'both',
        'time_allowed' => 10,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $detailId = DB::table('competition_details')->insertGetId([
        'competition_id' => $compId,
        'coach_name' => 'Test Coach',
        'city' => 'Test City',
        'start_date' => now(),
        'end_date' => now()->addDays(7),
        'start_time' => '08:00:00',
        'end_time' => '17:00:00',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $compUserId = DB::table('competition_users')->insertGetId([
        'user_id' => $user->id,
        'competition_id' => $compId,
        'competition_detail_id' => $detailId,
        'status' => 'accepted',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $resId = DB::table('competition_results')->insertGetId([
        'competition_user_id' => $compUserId,
        'exercise_id' => 1,
        'score' => 85.5,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('competition_result_videos')->insert([
        'competition_result_id' => $resId,
        'youtube_link' => 'https://youtube.com/watch?v=abcdef',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->getJson("/api/v1/user/{$user->id}/competition-video");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'competition_result_video_id',
                    'name',
                    'photo',
                    'youtube_link',
                    'exercise_id',
                    'exercise_name',
                    'exercise_image',
                ]
            ]
        ])
        ->assertJsonFragment([
            'name' => 'John Doe',
            'youtube_link' => 'https://youtube.com/watch?v=abcdef',
            'exercise_id' => 1,
            'exercise_name' => 'Pushups',
            'exercise_image' => asset('storage/exercise_images/pushup.png'),
        ]);
});

test('it returns 404 for non-existent user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->getJson("/api/v1/user/999999/competition-video");

    $response->assertNotFound();
});
