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
        Schema::create('question_tags', function (Blueprint $table) {
            $table->primary(['question_id', 'tag_id']);
            $table->uuid('question_id')->nullable();
            $table->foreign('question_id', 'fk-question-tag-1')
                ->references('id')
                ->on('questions')
                ->onDelete('set null')
                ->onUpdate('no action');
            $table->uuid('tag_id')->nullable();
            $table->foreign('tag_id', 'fk-question-tag-2')
                ->references('id')
                ->on('tags')
                ->onDelete('set null')
                ->onUpdate('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_tags');
    }
};
