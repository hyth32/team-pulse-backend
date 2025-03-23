<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Uuid::uuid7()->toString());

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
