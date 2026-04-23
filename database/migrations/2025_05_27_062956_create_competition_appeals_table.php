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
        Schema::create('competition_appeals', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreignId('competition_video_id')->constrained('competition_videos')->onDelete('cascade');
            $table->longText('appeal_text');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_appeals');
    }
};
