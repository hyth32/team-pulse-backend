<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topic_questions', function (Blueprint $table) {
            $table->primary(['template_id', 'question_id', 'topic_id']);

            $table->foreignUuid('template_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('topic_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('question_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_questions');
    }
};
