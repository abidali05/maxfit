<?php

use App\Models\Coach;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\GroupExercise;
use App\Models\GroupExerciseSubmission;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

beforeEach(function () {
    if (DB::getDriverName() === 'sqlite') {
        DB::statement('PRAGMA foreign_keys = OFF;');
    }

    Schema::table('users', function ($table) {
        if (!Schema::hasColumn('users', 'role')) $table->string('role')->default('user');
        if (!Schema::hasColumn('users', 'status')) $table->string('status')->default('active');
    });

    Schema::table('coaches', function ($table) {
        if (!Schema::hasColumn('coaches', 'password')) $table->string('password')->nullable();
        if (!Schema::hasColumn('coaches', 'status')) $table->string('status')->default('active');
    });

    if (!Schema::hasTable('organisations')) {
        Schema::create('organisations', function ($table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('type')->nullable();
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('group_exercises')) {
        Schema::create('group_exercises', function ($table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('exercise_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('group_exercise_submissions')) {
        Schema::create('group_exercise_submissions', function ($table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->integer('user_id');
            $table->unsignedBigInteger('exercise_id');
            $table->integer('count')->default(0);
            $table->date('submitted_date');
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('exercise_categories')) {
        Schema::create('exercise_categories', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('tag')->nullable();
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('exercises')) {
        Schema::create('exercises', function ($table) {
            $table->id();
            $table->unsignedBigInteger('exercise_category_id')->nullable();
            $table->string('name');
            $table->string('genz')->default('both');
            $table->string('exercise_type')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    if (!DB::table('exercise_categories')->where('id', 1)->exists()) {
        DB::table('exercise_categories')->insert([
            'id' => 1,
            'name' => 'Cardio',
            'tag' => 'cardio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
});

test('coach can view exercise reports with summary stats and athlete breakdowns', function () {
    $coach = Coach::create([
        'name' => 'Coach Farhan',
        'email' => 'farhan@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $athlete1 = User::factory()->create(['name' => 'Ali Khan', 'email' => 'ali@example.com', 'role' => 'user']);
    $athlete2 = User::factory()->create(['name' => 'Bilal Ahmed', 'email' => 'bilal@example.com', 'role' => 'user']);

    $group = Group::create([
        'coach_id' => $coach->id,
        'name' => 'Senior Squad',
        'status' => 'active',
    ]);

    GroupUser::create(['group_id' => $group->id, 'user_id' => $athlete1->id, 'status' => 'accepted']);
    GroupUser::create(['group_id' => $group->id, 'user_id' => $athlete2->id, 'status' => 'accepted']);

    $exercise = Exercise::create([
        'id' => 10,
        'exercise_category_id' => 1,
        'name' => 'Burpees',
        'genz' => 'both',
        'exercise_type' => 'count',
    ]);

    GroupExerciseSubmission::create([
        'group_id' => $group->id,
        'user_id' => $athlete1->id,
        'exercise_id' => 10,
        'count' => 25,
        'submitted_date' => now()->toDateString(),
    ]);

    GroupExerciseSubmission::create([
        'group_id' => $group->id,
        'user_id' => $athlete2->id,
        'exercise_id' => 10,
        'count' => 15,
        'submitted_date' => now()->toDateString(),
    ]);

    // 1. View Reports Index
    $response = $this->actingAs($coach, 'coach')
        ->get(route('coach.reports.index', ['group_id' => $group->id]));

    $response->assertOk()
        ->assertViewIs('coach.reports.index')
        ->assertSee('Senior Squad')
        ->assertSee('Ali Khan')
        ->assertSee('Bilal Ahmed')
        ->assertSee('Burpees')
        ->assertSee('40'); // 25 + 15 = 40 total reps

    // 2. Filter by single athlete (Ali Khan only)
    $filteredResponse = $this->actingAs($coach, 'coach')
        ->get(route('coach.reports.index', [
            'group_id' => $group->id,
            'user_ids' => [$athlete1->id],
        ]));

    $filteredResponse->assertOk()
        ->assertSee('Ali Khan');
    
    $athleteStats = $filteredResponse->viewData('athleteStats');
    expect($athleteStats->pluck('user.name')->all())->toContain('Ali Khan')
        ->not->toContain('Bilal Ahmed');

    // 3. Export CSV
    $csvResponse = $this->actingAs($coach, 'coach')
        ->get(route('coach.reports.export-csv', [
            'group_id' => $group->id,
        ]));

    $csvResponse->assertOk();
    expect($csvResponse->headers->get('content-type'))->toContain('text/csv');
});
