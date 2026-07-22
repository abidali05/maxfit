<?php

use App\Models\User;
use App\Models\PhysicalAssessment;
use App\Mail\ProfileReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();

    // Disable foreign key checks for SQLite testing
    DB::statement('PRAGMA foreign_keys = OFF;');

    // 1. Add missing columns to users table for SQLite testing
    Schema::table('users', function ($table) {
        if (!Schema::hasColumn('users', 'cnic')) $table->string('cnic')->nullable();
        if (!Schema::hasColumn('users', 'dob')) $table->date('dob')->nullable();
        if (!Schema::hasColumn('users', 'role')) $table->string('role')->default('user');
        if (!Schema::hasColumn('users', 'organisation_type')) $table->unsignedBigInteger('organisation_type')->nullable();
        if (!Schema::hasColumn('users', 'organisation_id')) $table->unsignedBigInteger('organisation_id')->nullable();
        if (!Schema::hasColumn('users', 'class')) $table->string('class')->nullable();
        if (!Schema::hasColumn('users', 'guardian_name')) $table->string('guardian_name')->nullable();
        if (!Schema::hasColumn('users', 'address')) $table->text('address')->nullable();
        if (!Schema::hasColumn('users', 'guardian_cnic')) $table->string('guardian_cnic')->nullable();
        if (!Schema::hasColumn('users', 'profile_reminder_sent_at')) $table->timestamp('profile_reminder_sent_at')->nullable();
    });

    // 2. Create physical_assessments table if it doesn't exist
    if (!Schema::hasTable('physical_assessments')) {
        Schema::create('physical_assessments', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('height_cm', 8, 2)->nullable();
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->decimal('bmi', 8, 2)->nullable();
            $table->string('gender')->nullable();
            $table->string('exercise_type')->nullable();
            $table->timestamps();
        });
    }

    // 3. Create goals table if it doesn't exist (with all columns used in test)
    if (!Schema::hasTable('goals')) {
        Schema::create('goals', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('set_id')->nullable();
            $table->unsignedBigInteger('exercise_id')->nullable();
            $table->string('value')->nullable();
            $table->text('days')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    } else {
        Schema::table('goals', function ($table) {
            if (!Schema::hasColumn('goals', 'set_id')) $table->unsignedBigInteger('set_id')->nullable();
            if (!Schema::hasColumn('goals', 'exercise_id')) $table->unsignedBigInteger('exercise_id')->nullable();
            if (!Schema::hasColumn('goals', 'value')) $table->string('value')->nullable();
            if (!Schema::hasColumn('goals', 'days')) $table->text('days')->nullable();
            if (!Schema::hasColumn('goals', 'start_date')) $table->date('start_date')->nullable();
            if (!Schema::hasColumn('goals', 'end_date')) $table->date('end_date')->nullable();
        });
    }

    // 4. Create other tables used in cleanup block if they don't exist
    $tables = [
        'user_exercise_assessment',
        'competition_appeals',
        'daily_assessments',
        'medical_assessment_answers',
        'personal_access_tokens'
    ];

    foreach ($tables as $table) {
        if (!Schema::hasTable($table)) {
            Schema::create($table, function ($t) use ($table) {
                $t->id();
                if ($table === 'personal_access_tokens') {
                    $t->unsignedBigInteger('tokenable_id');
                    $t->string('tokenable_type');
                } else {
                    $t->unsignedBigInteger('user_id');
                }
                $t->timestamps();
            });
        }
    }

    // 5. Seed required database tables for validation constraints
    if (!DB::table('organisation_types')->where('id', 1)->exists()) {
        DB::table('organisation_types')->insert([
            'id' => 1,
            'name' => 'School',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    if (!DB::table('organisations')->where('id', 1)->exists()) {
        DB::table('organisations')->insert([
            'id' => 1,
            'organisation_type_id' => 1,
            'name' => 'Test Organisation',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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

    if (!DB::table('exercises')->where('id', 1)->exists()) {
        DB::table('exercises')->insert([
            'id' => 1,
            'exercise_category_id' => 1,
            'name' => 'Pushups',
            'genz' => 'fatherfits',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
});

it('does not notify or delete complete users', function () {
    // Create a complete user (profile updated + physical assessment created)
    $user = User::factory()->create([
        'name' => 'John Doe',
        'cnic' => '1234567890123',
        'dob' => '1995-05-15',
        'organisation_type' => 1,
        'organisation_id' => 1,
        'class' => 'A',
        'guardian_name' => 'Jane Doe',
        'address' => '123 Main St',
        'guardian_cnic' => '9876543210987',
        'created_at' => now()->subHours(30),
    ]);

    PhysicalAssessment::create([
        'user_id' => $user->id,
        'height_cm' => 175.5,
        'weight_kg' => 70.2,
        'bmi' => 22.8,
        'gender' => 'Male',
        'exercise_type' => 'Cardio',
    ]);

    $this->artisan('app:clean-incomplete-profiles')
        ->expectsOutput('Starting incomplete profiles check...')
        ->assertExitCode(0);

    Mail::assertNotSent(ProfileReminderMail::class);
    expect(User::find($user->id))->not->toBeNull();
});

it('does not notify incomplete users registered less than 24 hours ago', function () {
    // Incomplete user registered 5 hours ago
    $user = User::factory()->create([
        'name' => 'John Doe',
        'created_at' => now()->subHours(5),
    ]);

    $this->artisan('app:clean-incomplete-profiles')->assertExitCode(0);

    Mail::assertNotSent(ProfileReminderMail::class);
    expect($user->fresh()->profile_reminder_sent_at)->toBeNull();
});

it('notifies incomplete users registered >= 24 hours ago', function () {
    // Incomplete user registered 25 hours ago
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'incomplete@example.com',
        'created_at' => now()->subHours(25),
    ]);

    $this->artisan('app:clean-incomplete-profiles')
        ->expectsOutput('Starting incomplete profiles check...')
        ->assertExitCode(0);

    Mail::assertSent(ProfileReminderMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });

    expect($user->fresh()->profile_reminder_sent_at)->not->toBeNull();
});

it('deletes incomplete users notified >= 24 hours ago and cleans up their database records', function () {
    // Incomplete user registered 50 hours ago, notified 25 hours ago
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'to-delete@example.com',
        'created_at' => now()->subHours(50),
        'profile_reminder_sent_at' => now()->subHours(25),
    ]);

    // Create related database records to test cascading/manual deletes
    DB::table('goals')->insert([
        'user_id' => $user->id,
        'set_id' => 1,
        'exercise_id' => 1,
        'value' => 10,
        'days' => '["M"]',
        'start_date' => '2026-07-22',
        'end_date' => '2026-08-22',
    ]);

    $this->artisan('app:clean-incomplete-profiles')->assertExitCode(0);

    // User should be deleted
    expect(User::find($user->id))->toBeNull();
    // Related goal record should be deleted
    expect(DB::table('goals')->where('user_id', $user->id)->first())->toBeNull();
});

it('does not delete incomplete users notified less than 24 hours ago', function () {
    // Incomplete user registered 26 hours ago, notified 2 hours ago
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'keep@example.com',
        'created_at' => now()->subHours(26),
        'profile_reminder_sent_at' => now()->subHours(2),
    ]);

    $this->artisan('app:clean-incomplete-profiles')->assertExitCode(0);

    expect(User::find($user->id))->not->toBeNull();
});

it('does not notify or delete incomplete admins or other non-user roles', function () {
    // Incomplete admin registered 30 hours ago
    $admin = User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'role' => 'admin',
        'created_at' => now()->subHours(30),
    ]);

    // Incomplete admin registered 50 hours ago, notified 25 hours ago
    $oldAdmin = User::factory()->create([
        'name' => 'Old Admin',
        'email' => 'oldadmin@example.com',
        'role' => 'admin',
        'created_at' => now()->subHours(50),
        'profile_reminder_sent_at' => now()->subHours(25),
    ]);

    $this->artisan('app:clean-incomplete-profiles')->assertExitCode(0);

    // Admin should not be notified
    Mail::assertNotSent(ProfileReminderMail::class);
    expect($admin->fresh()->profile_reminder_sent_at)->toBeNull();

    // Old Admin should not be deleted
    expect(User::find($oldAdmin->id))->not->toBeNull();
});
