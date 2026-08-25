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
        if (!Schema::hasTable('exercise_sub_groups')) {
            Schema::create('exercise_sub_groups', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exercise_group_id');
                $table->string('name');
                $table->string('sub_title')->nullable();
                $table->string('image')->nullable();
                $table->string('status')->default('active');
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->foreign('exercise_group_id', 'ex_sub_grp_group_id_fk')
                    ->references('id')->on('exercise_groups')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('exercise_sub_group_items')) {
            Schema::create('exercise_sub_group_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exercise_sub_group_id');
                $table->unsignedBigInteger('exercise_id');
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->foreign('exercise_sub_group_id', 'ex_sub_grp_items_sub_id_fk')
                    ->references('id')->on('exercise_sub_groups')->onDelete('cascade');
                $table->foreign('exercise_id', 'ex_sub_grp_items_ex_id_fk')
                    ->references('id')->on('exercises')->onDelete('cascade');
                $table->unique(['exercise_sub_group_id', 'exercise_id'], 'ex_sub_grp_item_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_sub_group_items');
        Schema::dropIfExists('exercise_sub_groups');
    }
};
