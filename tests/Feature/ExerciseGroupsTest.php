<?php

use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\ExerciseGroup;
use App\Models\ExerciseGroupItem;
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
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('exercise_group_items')) {
        Schema::create('exercise_group_items', function ($table) {
            $table->id();
            $table->unsignedBigInteger('exercise_group_id');
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

test('admin can manage exercise groups with exercise ordering', function () {
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
        'name' => 'Fast Bowler Run-Up Drill',
        'genz' => 'both',
        'exercise_type' => 'reps',
        'fitness_level' => 'Intermediate',
        'video_time' => '12 reps',
    ]);

    // 1. List groups
    $response = $this->actingAs($admin)
        ->get(route('exercise-groups.index'));
    $response->assertOk()
        ->assertViewIs('exercise_groups.index')
        ->assertDontSee('Order'); // Group order column removed

    // 2. View Create Form
    $response = $this->actingAs($admin)
        ->get(route('exercise-groups.create'));
    $response->assertOk()
        ->assertViewIs('exercise_groups.create')
        ->assertSee('Batting Stance Hold')
        ->assertSee('Fast Bowler Run-Up Drill')
        ->assertSee('Selected Order');

    // 3. Store new group with specific exercise ordering (exercise 2 first, then exercise 1)
    $response = $this->actingAs($admin)
        ->post(route('exercise-groups.store'), [
            'name' => 'Cricket',
            'sub_title' => 'Popular by Sport',
            'status' => 'active',
            'exercise_ids' => [2, 1],
        ]);

    $response->assertRedirect(route('exercise-groups.index'));
    $this->assertDatabaseHas('exercise_groups', [
        'name' => 'Cricket',
        'sub_title' => 'Popular by Sport',
    ]);
    
    // Assert order preserved in items
    $this->assertDatabaseHas('exercise_group_items', [
        'exercise_id' => 2,
        'order' => 0,
    ]);
    $this->assertDatabaseHas('exercise_group_items', [
        'exercise_id' => 1,
        'order' => 1,
    ]);

    $group = ExerciseGroup::where('name', 'Cricket')->first();
    expect($group->exercises->pluck('id')->all())->toEqual([2, 1]);

    // 4. Edit group
    $response = $this->actingAs($admin)
        ->get(route('exercise-groups.edit', $group->id));
    $response->assertOk()
        ->assertViewIs('exercise_groups.edit');

    // 5. Update group
    $response = $this->actingAs($admin)
        ->put(route('exercise-groups.update', $group->id), [
            'name' => 'Cricket Updated',
            'sub_title' => 'Special Sport Edition',
            'status' => 'active',
            'exercise_ids' => [1],
        ]);

    $response->assertRedirect(route('exercise-groups.index'));
    $this->assertDatabaseHas('exercise_groups', [
        'id' => $group->id,
        'name' => 'Cricket Updated',
    ]);
    $this->assertDatabaseMissing('exercise_group_items', [
        'exercise_group_id' => $group->id,
        'exercise_id' => 2,
    ]);
});

test('mobile API returns popular exercise groups with default 1st group, pagination, and exact exercise order', function () {
    $group1 = ExerciseGroup::create([
        'name' => 'Cricket',
        'sub_title' => 'Popular by Sport',
        'status' => 'active',
    ]);

    $group2 = ExerciseGroup::create([
        'name' => 'Football',
        'sub_title' => 'Popular by Sport',
        'status' => 'active',
    ]);

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

    // Insert ex2 first (order 0), then ex1 (order 1)
    ExerciseGroupItem::create(['exercise_group_id' => $group1->id, 'exercise_id' => 102, 'order' => 0]);
    ExerciseGroupItem::create(['exercise_group_id' => $group1->id, 'exercise_id' => 101, 'order' => 1]);

    // 1. Fetch API without group_id (Should auto-select 1st group: Cricket)
    $response = $this->getJson('/api/v1/exercise-groups');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'groups' => [
                    '*' => [
                        'id',
                        'name',
                        'sub_title',
                        'is_selected',
                    ]
                ],
                'selected_group' => [
                    'id',
                    'name',
                    'sub_title',
                    'total_items',
                ],
                'exercises' => [
                    'current_page',
                    'total',
                    'per_page',
                    'data' => [
                        '*' => [
                            'item_number',
                            'id',
                            'name',
                            'category',
                            'fitness_level',
                            'duration_or_reps',
                        ]
                    ]
                ]
            ]
        ])
        ->assertJsonFragment([
            'name' => 'Cricket',
            'is_selected' => true,
        ]);

    $exercisesData = $response->json('data.exercises.data');
    expect($exercisesData[0]['id'])->toBe(102);
    expect($exercisesData[0]['name'])->toBe('Fast Bowler Run-Up Drill');
    expect($exercisesData[1]['id'])->toBe(101);
    expect($exercisesData[1]['name'])->toBe('Batting Stance Hold');

    // 2. Fetch API for specific group (Football)
    $responseFootball = $this->getJson("/api/v1/exercise-groups?group_id={$group2->id}");
    $responseFootball->assertOk()
        ->assertJsonFragment([
            'name' => 'Football',
            'is_selected' => true,
            'total_items' => 0,
        ]);
});
