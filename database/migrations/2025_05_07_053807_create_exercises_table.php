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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('genz');
            $table->string('exercise_type')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('video_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
