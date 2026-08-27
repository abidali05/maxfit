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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreignId('set_id')->nullable()->constrained('sets')->nullOnDelete();
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->string('value');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->longText('days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
