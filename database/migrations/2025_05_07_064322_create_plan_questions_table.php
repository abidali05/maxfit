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
        Schema::create('plan_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->enum('type', ['input','textarea','selection'])->default('input');
            $table->enum('is_required', [0,1])->default(0); // 0 means not
            $table->string('answer_options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_questions');
    }
};
