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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age_group');
            $table->enum('genz', ['both','motherfits', 'fatherfits']);
            $table->string('country');
            $table->integer('time_allowed')->nullable();
            $table->foreignId('org_type')->nullable()->constrained('organisation_types')->onDelete('set null');
            $table->foreignId('org')->nullable()->constrained('organisations')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
