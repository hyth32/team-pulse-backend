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
        Schema::create('test_questions', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('test_id');
            $table->foreign('test_id', 'fk-test-question-1')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->uuid('question_id');
            $table->foreign('question_id', 'fk-test-question-2')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->uuid('topic_id')->nullable();
            $table->foreign('topic_id', 'fk-question-topic-1')
                ->references('id')
                ->on('topics')
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
        Schema::dropIfExists('test_questions');
    }
};
