<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->primary(['assigned_test_id', 'user_id']);

            $table->foreignUuid('assigned_test_id')->constrained()->cascadeOnDelete()->noActionOnUpdate();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete()->noActionOnUpdate();
            $table->foreignUuid('question_id')->constrained()->cascadeOnDelete()->noActionOnUpdate();

            $table->text('answer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
