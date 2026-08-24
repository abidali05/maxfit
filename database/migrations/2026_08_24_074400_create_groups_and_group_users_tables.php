<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id');
            $table->string('name');
            $table->string('age_group')->nullable();
            $table->string('gender')->nullable();
            $table->string('genz')->nullable();
            $table->string('country')->nullable();
            $table->json('org_types')->nullable();
            $table->json('orgs')->nullable();
            $table->string('status')->default('active'); // active, suspended
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
        });

        Schema::create('group_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->integer('user_id');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_users');
        Schema::dropIfExists('groups');
    }
};
