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
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('text')->nullable();
            $table->uuid('image_id')->nullable();
            $table->foreign('image_id', 'fk-answer-file-1')
                ->references('id')
                ->on('files')
                ->onDelete('set null')
                ->onUpdate('no action');
            $table->uuid('question_id')->nullable();
            $table->foreign('question_id', 'fk-answer-question-1')
                ->references('id')
                ->on('questions')
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
        Schema::dropIfExists('answers');
    }
};
