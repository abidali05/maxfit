<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('goal_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('goal_id')->constrained()->onDelete('cascade');
            $table->date('scheduled_date');
            $table->json('days'); // ['monday', 'tuesday', 'wednesday']
            $table->json('exercises'); // [{'exercise_id': 1, 'sets': 3, 'reps': 10}, ...]
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('goal_schedules');
    }
};