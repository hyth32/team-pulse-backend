<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answer_tag_points', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Uuid::uuid7()->toString());
            $table->uuid('answer_id');
            $table->foreign('answer_id', 'fk-points-answer-1')
                ->references('id')
                ->on('answers')
                ->onUpdate('no action')
                ->onDelete('cascade');
            $table->uuid('tag_id');
            $table->foreign('tag_id', 'fk-points-tag-1')
                ->references('id')
                ->on('tags')
                ->onUpdate('no action')
                ->onDelete('cascade');
            $table->float('point_count');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_tag_points');
    }
};
