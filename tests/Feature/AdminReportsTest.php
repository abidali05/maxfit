<?php

use App\Models\User;
use App\Models\Coach;
use App\Models\Group;
use App\Models\Exercise;
use App\Models\ExerciseCategory;
use App\Models\GroupExerciseSubmission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    DB::statement('PRAGMA foreign_keys = OFF;');

    Schema::table('users', function ($table) {
        if (!Schema::hasColumn('users', 'role')) $table->string('role')->default('user');
        if (!Schema::hasColumn('users', 'status')) $table->string('status')->default('active');
        if (!Schema::hasColumn('users', 'image')) $table->string('image')->nullable();
    });

    Schema::table('coaches', function ($table) {
        if (!Schema::hasColumn('coaches', 'password')) $table->string('password')->nullable();
        if (!Schema::hasColumn('coaches', 'status')) $table->string('status')->default('active');
    });

    if (!Schema::hasTable('countries')) {
        Schema::create('countries', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('group_exercise_submissions')) {
        Schema::create('group_exercise_submissions', function ($table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exercise_id');
            $table->integer('count');
            $table->date('submitted_date');
            $table->timestamps();
        });
    }
});

it('allows admin to view group reports, download receipt pdf, and export csv', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'image' => 'avatar.jpg',
    ]);

    $coach = Coach::create([
        'name' => 'Coach Admin Test',
        'email' => 'coachadmintest@example.com',
        'password' => bcrypt('password'),
    ]);

    $group = Group::create([
        'coach_id' => $coach->id,
        'name' => 'Admin Test Squad',
        'status' => 'active',
    ]);

    $athlete = User::create([
        'name' => 'Athlete John',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
    ]);

    $cat = ExerciseCategory::create(['name' => 'Strength']);
    $exercise = Exercise::create([
        'name' => 'Pushup Drill',
        'exercise_category_id' => $cat->id,
        'exercise_type' => 'count',
        'genz' => 'fatherfits',
        'fitness_level' => 'both',
        'gender' => 'both',
    ]);

    GroupExerciseSubmission::create([
        'group_id' => $group->id,
        'user_id' => $athlete->id,
        'exercise_id' => $exercise->id,
        'count' => 50,
        'submitted_date' => '2026-08-27',
    ]);

    // 1. Admin can view group reports index
    $response = $this->actingAs($admin)->get(route('admin.reports.index'));
    $response->assertStatus(200);
    $response->assertSee('Admin Test Squad');
    $response->assertSee('Pushup Drill');
    $response->assertSee('50');

    // 2. Admin can download PDF receipt
    $pdfRes = $this->actingAs($admin)->get(route('admin.reports.download-receipt', ['group_id' => $group->id]));
    $pdfRes->assertStatus(200);
    expect($pdfRes->headers->get('content-type'))->toBe('application/pdf');

    // 3. Admin can export CSV
    $csvRes = $this->actingAs($admin)->get(route('admin.reports.export-csv', ['group_id' => $group->id]));
    $csvRes->assertStatus(200);
    expect($csvRes->headers->get('content-type'))->toContain('text/csv');
});
