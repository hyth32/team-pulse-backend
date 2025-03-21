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
        Schema::create('assigned_test_groups', function (Blueprint $table) {
            $table->primary(['assigned_test_id', 'group_id']);

            $table->foreignUuid('assigned_test_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('group_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_test_groups');
    }
};
