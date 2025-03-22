<?php

use App\Enums\Test\TopicCompletionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_test_completions', function (Blueprint $table) {
            $table->primary(['user_id', 'assigned_test_id']);

            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('assigned_test_id')->constrained()->cascadeOnDelete();

            $table->integer('completion_status')->default(TopicCompletionStatus::NotPassed->value());
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_test_completions');
    }
};
