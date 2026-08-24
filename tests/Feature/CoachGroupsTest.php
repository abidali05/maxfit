<?php

use App\Models\User;
use App\Models\Coach;
use App\Models\Group;
use App\Models\GroupUser;
use App\Mail\GroupInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();

    DB::statement('PRAGMA foreign_keys = OFF;');

    Schema::table('users', function ($table) {
        if (!Schema::hasColumn('users', 'role')) $table->string('role')->default('user');
        if (!Schema::hasColumn('users', 'status')) $table->string('status')->default('active');
        if (!Schema::hasColumn('users', 'organisation_id')) $table->unsignedBigInteger('organisation_id')->nullable();
        if (!Schema::hasColumn('users', 'organisation_type')) $table->unsignedBigInteger('organisation_type')->nullable();
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

    if (!Schema::hasTable('cities')) {
        Schema::create('cities', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('organisation_types')) {
        Schema::create('organisation_types', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('provinces')) {
        Schema::create('provinces', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    Schema::table('organisations', function ($table) {
        if (!Schema::hasColumn('organisations', 'type')) $table->unsignedBigInteger('type')->nullable();
    });

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
            $table->string('image')->nullable();
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

    if (!DB::table('exercise_categories')->where('id', 1)->exists()) {
        DB::table('exercise_categories')->insert([
            'id' => 1,
            'name' => 'Strength',
            'tag' => 'strength',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
});

test('coach can list groups and view create form', function () {
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $response = $this->actingAs($coach, 'coach')
        ->get(route('coach.groups.index'));

    $response->assertOk()
        ->assertViewIs('coach.groups.index');

    $response = $this->actingAs($coach, 'coach')
        ->get(route('coach.groups.create'));

    $response->assertOk()
        ->assertViewIs('coach.groups.create');
});

test('coach can create group and invite athletes', function () {
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $athlete1 = User::factory()->create(['role' => 'user', 'status' => 'active']);
    $athlete2 = User::factory()->create(['role' => 'user', 'status' => 'active']);

    $response = $this->actingAs($coach, 'coach')
        ->post(route('coach.groups.store'), [
            'name' => 'Gold Medalists',
            'user_ids' => [$athlete1->id, $athlete2->id],
        ]);

    $response->assertRedirect(route('coach.groups.index'));

    $this->assertDatabaseHas('groups', [
        'name' => 'Gold Medalists',
        'coach_id' => $coach->id,
        'status' => 'active',
    ]);

    $group = Group::where('name', 'Gold Medalists')->first();

    $this->assertDatabaseHas('group_users', [
        'group_id' => $group->id,
        'user_id' => $athlete1->id,
        'status' => 'pending',
    ]);

    $this->assertDatabaseHas('group_users', [
        'group_id' => $group->id,
        'user_id' => $athlete2->id,
        'status' => 'pending',
    ]);

    Mail::assertSent(GroupInvitationMail::class, 2);
});

test('athlete can view invitations and respond via API', function () {
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $athlete = User::factory()->create(['role' => 'user', 'status' => 'active']);

    $group = Group::create([
        'coach_id' => $coach->id,
        'name' => 'Elite Squad',
        'status' => 'active',
    ]);

    GroupUser::create([
        'group_id' => $group->id,
        'user_id' => $athlete->id,
        'status' => 'pending',
    ]);

    // 1. Get Invitations API
    $response = $this->actingAs($athlete, 'sanctum')
        ->getJson('/api/v1/user/groups/requests');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'pending_count',
                'active_count',
                'groups' => [
                    '*' => [
                        'group_id',
                        'group_name',
                        'coach_name',
                        'athletes_count',
                        'requested_date',
                        'status',
                    ]
                ]
            ]
        ]);

    // 2. Respond to invitation API (accept)
    $response = $this->actingAs($athlete, 'sanctum')
        ->postJson('/api/v1/user/groups/respond', [
            'group_id' => $group->id,
            'status' => 'accepted',
        ]);

    $response->assertOk();

    $this->assertDatabaseHas('group_users', [
        'group_id' => $group->id,
        'user_id' => $athlete->id,
        'status' => 'accepted',
    ]);
});

test('admin can list, view, and suspend groups', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $group = Group::create([
        'coach_id' => $coach->id,
        'name' => 'Future Stars',
        'status' => 'active',
    ]);

    // 1. Admin Index
    $response = $this->actingAs($admin)
        ->get(route('groups.index'));

    $response->assertOk()
        ->assertViewIs('admin.groups.index');

    // 2. Admin Show
    $response = $this->actingAs($admin)
        ->get(route('groups.show', $group->id));

    $response->assertOk()
        ->assertViewIs('admin.groups.show');

    // 3. Admin Suspend
    $response = $this->actingAs($admin)
        ->post(route('groups.suspend', $group->id));

    $response->assertRedirect();

    $this->assertDatabaseHas('groups', [
        'id' => $group->id,
        'status' => 'suspended',
    ]);

    // 4. Admin Unsuspend
    $response = $this->actingAs($admin)
        ->post(route('groups.unsuspend', $group->id));

    $response->assertRedirect();

    $this->assertDatabaseHas('groups', [
        'id' => $group->id,
        'status' => 'active',
    ]);
});

test('coach can query eligible users and organizations via AJAX', function () {
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);



    DB::table('organisation_types')->insert([
        'id' => 1,
        'name' => 'School',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $orgId = DB::table('organisations')->insertGetId([
        'name' => 'ACME Club',
        'type' => 1,
        'organisation_type_id' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $athlete = User::factory()->create([
        'role' => 'user',
        'status' => 'active',
        'organisation_id' => $orgId,
        'organisation_type' => 1,
    ]);

    // 1. Get organizations by types
    $response = $this->actingAs($coach, 'coach')
        ->getJson(route('coach.groups.organizations-by-types', ['org_types' => [1]]));

    $response->assertOk()
        ->assertJsonStructure(['results'])
        ->assertJsonFragment(['name' => 'ACME Club']);

    // 2. Get eligible users
    $response = $this->actingAs($coach, 'coach')
        ->getJson(route('coach.groups.eligible-users', ['orgs' => [$orgId]]));

    $response->assertOk()
        ->assertJsonFragment(['name' => $athlete->name]);
});

test('coach can edit group, update instructions, and assign multiple exercises', function () {
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $athlete = User::factory()->create(['role' => 'user', 'status' => 'active']);

    $group = Group::create([
        'coach_id' => $coach->id,
        'name' => 'Elite Squad',
        'status' => 'active',
    ]);

    // View Edit Form
    $response = $this->actingAs($coach, 'coach')
        ->get(route('coach.groups.edit', $group->id));
    $response->assertOk()
        ->assertViewIs('coach.groups.edit');

    // Update Instructions & details via PUT
    $response = $this->actingAs($coach, 'coach')
        ->put(route('coach.groups.update', $group->id), [
            'name' => 'Elite Squad Updated',
            'instructions' => '<p>Please complete all exercise reps.</p>',
            'user_ids' => [$athlete->id],
        ]);

    $response->assertRedirect(route('coach.groups.index'));
    $this->assertDatabaseHas('groups', [
        'id' => $group->id,
        'name' => 'Elite Squad Updated',
        'instructions' => '<p>Please complete all exercise reps.</p>',
    ]);

    // Seed an exercise
    $exercise = \App\Models\Exercise::create([
        'id' => 123,
        'exercise_category_id' => 1,
        'name' => 'Pushups',
        'genz' => 'both',
    ]);

    // Assign Exercises with multi-select support (exercise_ids array)
    $response = $this->actingAs($coach, 'coach')
        ->post(route('coach.groups.assign-exercises', $group->id), [
            'assignments' => [
                [
                    'exercise_ids' => [123],
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(5)->toDateString(),
                ]
            ]
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('group_exercises', [
        'group_id' => $group->id,
        'exercise_id' => 123,
    ]);
});

test('athlete details API returns instructions and exercises assigned for today', function () {
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $athlete = User::factory()->create(['role' => 'user', 'status' => 'active']);

    $group = Group::create([
        'coach_id' => $coach->id,
        'name' => 'Elite Squad',
        'instructions' => '<p>Watch your posture.</p>',
        'status' => 'active',
    ]);

    GroupUser::create([
        'group_id' => $group->id,
        'user_id' => $athlete->id,
        'status' => 'accepted',
    ]);

    $exercise = \App\Models\Exercise::create([
        'id' => 99,
        'exercise_category_id' => 1,
        'name' => 'Burpees',
        'genz' => 'both',
    ]);

    // Active today
    \App\Models\GroupExercise::create([
        'group_id' => $group->id,
        'exercise_id' => 99,
        'start_date' => now()->subDay()->toDateString(),
        'end_date' => now()->addDay()->toDateString(),
    ]);

    // Fetch Details API
    $response = $this->actingAs($athlete, 'sanctum')
        ->getJson("/api/v1/user/groups/{$group->id}");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'group_id',
                'group_name',
                'coach_name',
                'athletes_count',
                'status',
                'group_status',
                'instructions',
                'exercises' => [
                    '*' => [
                        'id',
                        'name',
                        'category',
                        'image',
                        'exercise_type',
                        'unit',
                    ]
                ]
            ]
        ])
        ->assertJsonFragment([
            'instructions' => '<p>Watch your posture.</p>',
            'name' => 'Burpees',
            'group_status' => 'active',
            'unit' => 'per count',
        ]);
});

test('athlete can submit exercise counts and view updated progress in details API', function () {
    $coach = Coach::create([
        'name' => 'Coach John',
        'email' => 'coachjohn@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
    ]);

    $athlete = User::factory()->create(['role' => 'user', 'status' => 'active']);

    $group = Group::create([
        'coach_id' => $coach->id,
        'name' => 'Elite Squad',
        'status' => 'active',
    ]);

    GroupUser::create([
        'group_id' => $group->id,
        'user_id' => $athlete->id,
        'status' => 'accepted',
    ]);

    $exercise = \App\Models\Exercise::create([
        'id' => 88,
        'exercise_category_id' => 1,
        'name' => 'Sit Ups',
        'genz' => 'both',
    ]);

    \App\Models\GroupExercise::create([
        'group_id' => $group->id,
        'exercise_id' => 88,
        'start_date' => now()->subDay()->toDateString(),
        'end_date' => now()->addDay()->toDateString(),
    ]);

    // 1. Initially 0/1 submitted today
    $response = $this->actingAs($athlete, 'sanctum')
        ->getJson("/api/v1/user/groups/{$group->id}");

    $response->assertOk()
        ->assertJsonFragment([
            'total_exercises_count' => 1,
            'submitted_exercises_count' => 0,
            'is_submitted' => false,
            'count' => 0,
        ]);

    // 2. Submit exercise count (e.g. count = 5)
    $response = $this->actingAs($athlete, 'sanctum')
        ->postJson('/api/v1/user/groups/submit', [
            'group_id' => $group->id,
            'submissions' => [
                [
                    'exercise_id' => 88,
                    'count' => 5,
                ]
            ]
        ]);

    $response->assertOk()
        ->assertJsonFragment(['success' => true]);

    $this->assertDatabaseHas('group_exercise_submissions', [
        'group_id' => $group->id,
        'user_id' => $athlete->id,
        'exercise_id' => 88,
        'count' => 5,
        'submitted_date' => now()->toDateString(),
    ]);

    // 3. Details API now shows 1/1 submitted today and count = 5
    $response = $this->actingAs($athlete, 'sanctum')
        ->getJson("/api/v1/user/groups/{$group->id}");

    $response->assertOk()
        ->assertJsonFragment([
            'total_exercises_count' => 1,
            'submitted_exercises_count' => 1,
            'is_submitted' => true,
            'count' => 5,
        ]);
});
