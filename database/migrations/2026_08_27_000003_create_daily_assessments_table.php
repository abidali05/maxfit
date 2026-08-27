<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('daily_assessments')) {
            Schema::create('daily_assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('set_id')->nullable()->constrained('sets')->nullOnDelete();
                $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
                $table->integer('count')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_assessments');
    }
};
