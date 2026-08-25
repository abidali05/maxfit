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
        Schema::create('exercise_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sub_title')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('status')->default('active');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('exercise_group_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exercise_group_id');
            $table->unsignedBigInteger('exercise_id');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('exercise_group_id')->references('id')->on('exercise_groups')->onDelete('cascade');
            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
            $table->unique(['exercise_group_id', 'exercise_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_group_items');
        Schema::dropIfExists('exercise_groups');
    }
};
