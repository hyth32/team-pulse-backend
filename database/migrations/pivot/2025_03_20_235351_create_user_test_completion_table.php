<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_test_completions', function (Blueprint $table) {
            $table->primary(['user_id', 'topic_id']);

            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('assigned_test_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('topic_id')->constrained()->onDelete('cascade');

            $table->integer('completion_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_test_completions');
    }
};
