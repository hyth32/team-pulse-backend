<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_topics', function (Blueprint $table) {
            $table->primary(['question_id', 'topic_id']);

            $table->foreignUuid('question_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('topic_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_topics');
    }
};
