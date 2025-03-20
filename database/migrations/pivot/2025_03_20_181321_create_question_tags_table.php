<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_tags', function (Blueprint $table) {
            $table->primary(['question_id', 'tag_id']);
            
            $table->foreignUuid('question_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('tag_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_tags');
    }
};
