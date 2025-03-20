<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answer_tags', function (Blueprint $table) {
            $table->primary(['answer_id', 'tag_id']);

            $table->foreignUuid('answer_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('tag_id')->constrained()->onDelete('cascade');
            
            $table->float('point_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answer_tags');
    }
};
