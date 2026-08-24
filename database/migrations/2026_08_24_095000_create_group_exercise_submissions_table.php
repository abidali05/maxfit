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
        Schema::create('group_exercise_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->integer('user_id');
            $table->unsignedBigInteger('exercise_id');
            $table->integer('count')->default(0);
            $table->date('submitted_date');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');

            $table->unique(['group_id', 'user_id', 'exercise_id', 'submitted_date'], 'group_exercise_sub_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_exercise_submissions');
    }
};
