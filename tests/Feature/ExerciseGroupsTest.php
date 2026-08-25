<?php

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\ExerciseGroup;
use App\Models\ExerciseSubGroup;
use App\Models\ExerciseSubGroupItem;
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

    if (!Schema::hasTable('exercise_groups')) {
        Schema::create('exercise_groups', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('sub_title')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('exercise_sub_groups')) {
        Schema::create('exercise_sub_groups', function ($table) {
            $table->id();
            $table->unsignedBigInteger('exercise_group_id');
            $table->string('name');
            $table->string('sub_title')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('exercise_sub_group_items')) {
        Schema::create('exercise_sub_group_items', function ($table) {
            $table->id();
            $table->unsignedBigInteger('exercise_sub_group_id');
            $table->unsignedBigInteger('exercise_id');
            $table->integer('order')->default(0);
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
            $table->string('fitness_level')->nullable();
            $table->string('video_time')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('youtube_link')->nullable();
            $table->timestamps();
        });
    }

    if (!DB::table('exercise_categories')->where('id', 1)->exists()) {
        DB::table('exercise_categories')->insert([
            'id' => 1,
            'name' => 'Strength & Power',
            'tag' => 'strength',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
});

test('admin can manage exercise groups and sub-groups with exercise ordering', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $exercise1 = Exercise::create([
        'id' => 1,
        'exercise_category_id' => 1,
        'name' => 'Batting Stance Hold',
        'genz' => 'both',
        'exercise_type' => 'sec',
        'fitness_level' => 'Beginner',
        'video_time' => '30 sec',
    ]);

    $exercise2 = Exercise::create([
        'id' => 2,
        'exercise_category_id' => 1,
        'name' => 'Power Drive Drill',
        'genz' => 'both',
        'exercise_type' => 'reps',
        'fitness_level' => 'Intermediate',
        'video_time' => '12 reps',
    ]);

    // 1. List groups
    $response = $this->actingAs($admin)
        ->get(route('exercise-groups.index'));
    $response->assertOk()
        ->assertViewIs('exercise_groups.index');

    // 2. Create new Main Group
    $response = $this->actingAs($admin)
        ->post(route('exercise-groups.store'), [
            'name' => 'Cricket',
            'sub_title' => 'Popular by Sport',
            'status' => 'active',
        ]);

    $group = ExerciseGroup::where('name', 'Cricket')->first();
    expect($group)->not->toBeNull();
    $response->assertRedirect(route('exercise-groups.show', $group->id));

    // 3. View Group Show Page
    $response = $this->actingAs($admin)
        ->get(route('exercise-groups.show', $group->id));
    $response->assertOk()
        ->assertViewIs('exercise_groups.show')
        ->assertSee('Cricket');

    // 4. View Create Sub-Group Form
    $response = $this->actingAs($admin)
        ->get(route('exercise-sub-groups.create', ['group_id' => $group->id]));
    $response->assertOk()
        ->assertViewIs('exercise_sub_groups.create')
        ->assertSee('Batting Stance Hold');

    // 5. Store Sub-Group with ordered exercises (exercise 2 first, then exercise 1)
    $response = $this->actingAs($admin)
        ->post(route('exercise-sub-groups.store'), [
            'exercise_group_id' => $group->id,
            'name' => 'Batsman',
            'sub_title' => 'Batting Skills & Technique',
            'status' => 'active',
            'exercise_ids' => [2, 1],
        ]);

    $response->assertRedirect(route('exercise-groups.show', $group->id));
    $this->assertDatabaseHas('exercise_sub_groups', [
        'exercise_group_id' => $group->id,
        'name' => 'Batsman',
    ]);

    $subGroup = ExerciseSubGroup::where('name', 'Batsman')->first();
    expect($subGroup->exercises->pluck('id')->all())->toEqual([2, 1]);

    // 6. Edit Sub-Group
    $response = $this->actingAs($admin)
        ->get(route('exercise-sub-groups.edit', $subGroup->id));
    $response->assertOk()
        ->assertViewIs('exercise_sub_groups.edit');

    // 7. Update Sub-Group
    $response = $this->actingAs($admin)
        ->put(route('exercise-sub-groups.update', $subGroup->id), [
            'name' => 'Batsman Elite',
            'sub_title' => 'Advanced Batting Drills',
            'status' => 'active',
            'exercise_ids' => [1],
        ]);

    $response->assertRedirect(route('exercise-groups.show', $group->id));
    $this->assertDatabaseHas('exercise_sub_groups', [
        'id' => $subGroup->id,
        'name' => 'Batsman Elite',
    ]);
    $this->assertDatabaseMissing('exercise_sub_group_items', [
        'exercise_sub_group_id' => $subGroup->id,
        'exercise_id' => 2,
    ]);
});

test('mobile API returns groups, sub-groups, auto-selects 1st sub-group, and supports query params', function () {
    // Create Main Group 1: Cricket
    $cricket = ExerciseGroup::create([
        'name' => 'Cricket',
        'sub_title' => 'Popular by Sport',
        'status' => 'active',
    ]);

    // Create Sub-Groups for Cricket: Batsman (1st) and Bowler (2nd)
    $batsman = ExerciseSubGroup::create([
        'exercise_group_id' => $cricket->id,
        'name' => 'Batsman',
        'sub_title' => 'Batting drills',
        'status' => 'active',
        'order' => 0,
    ]);

    $bowler = ExerciseSubGroup::create([
        'exercise_group_id' => $cricket->id,
        'name' => 'Bowler',
        'sub_title' => 'Bowling drills',
        'status' => 'active',
        'order' => 1,
    ]);

    // Create Main Group 2: Football
    $football = ExerciseGroup::create([
        'name' => 'Football',
        'sub_title' => 'Popular by Sport',
        'status' => 'active',
    ]);

    $striker = ExerciseSubGroup::create([
        'exercise_group_id' => $football->id,
        'name' => 'Striker',
        'sub_title' => 'Finishing drills',
        'status' => 'active',
        'order' => 0,
    ]);

    // Exercises
    $ex1 = Exercise::create([
        'id' => 101,
        'exercise_category_id' => 1,
        'name' => 'Batting Stance Hold',
        'genz' => 'both',
        'exercise_type' => 'sec',
        'fitness_level' => 'Beginner',
        'video_time' => '30 sec',
    ]);

    $ex2 = Exercise::create([
        'id' => 102,
        'exercise_category_id' => 1,
        'name' => 'Fast Bowler Run-Up Drill',
        'genz' => 'both',
        'exercise_type' => 'reps',
        'fitness_level' => 'Intermediate',
        'video_time' => '12 reps',
    ]);

    // Assign ex1 to Batsman, ex2 to Bowler
    ExerciseSubGroupItem::create(['exercise_sub_group_id' => $batsman->id, 'exercise_id' => 101, 'order' => 0]);
    ExerciseSubGroupItem::create(['exercise_sub_group_id' => $bowler->id, 'exercise_id' => 102, 'order' => 0]);

    // 1. Initial Request (no params) -> Auto loads Cricket (1st group) & Batsman (1st sub-group)
    $response = $this->getJson('/api/v1/exercise-groups');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'groups' => [
                    '*' => ['id', 'name', 'sub_title', 'is_selected']
                ],
                'selected_group' => ['id', 'name', 'sub_title', 'total_sub_groups'],
                'sub_groups' => [
                    '*' => ['id', 'name', 'sub_title', 'is_selected']
                ],
                'selected_sub_group' => ['id', 'name', 'sub_title', 'total_items'],
                'exercises' => [
                    'current_page',
                    'total',
                    'per_page',
                    'data' => [
                        '*' => ['item_number', 'id', 'name', 'category', 'fitness_level', 'exercise_type']
                    ]
                ]
            ]
        ])
        ->assertJsonFragment([
            'name' => 'Cricket',
            'is_selected' => true,
        ])
        ->assertJsonFragment([
            'name' => 'Batsman',
            'is_selected' => true,
        ]);

    $exercisesData = $response->json('data.exercises.data');
    expect($exercisesData)->toHaveCount(1);
    expect($exercisesData[0]['id'])->toBe(101);
    expect($exercisesData[0]['name'])->toBe('Batting Stance Hold');

    // 2. Query Bowler Sub-Group: ?group_id=1&sub_group_id=2
    $responseBowler = $this->getJson("/api/v1/exercise-groups?group_id={$cricket->id}&sub_group_id={$bowler->id}");
    $responseBowler->assertOk()
        ->assertJsonFragment([
            'name' => 'Bowler',
            'is_selected' => true,
        ]);

    $bowlerExercises = $responseBowler->json('data.exercises.data');
    expect($bowlerExercises[0]['id'])->toBe(102);
    expect($bowlerExercises[0]['name'])->toBe('Fast Bowler Run-Up Drill');

    // 3. Query Football: ?group_id=2 -> Auto loads Striker (1st sub-group of Football)
    $responseFootball = $this->getJson("/api/v1/exercise-groups?group_id={$football->id}");
    $responseFootball->assertOk()
        ->assertJsonFragment([
            'name' => 'Football',
            'is_selected' => true,
        ])
        ->assertJsonFragment([
            'name' => 'Striker',
            'is_selected' => true,
        ]);
});
