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
        Schema::create('test_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('test_id');
            $table->foreign('test_id', 'fk-test-1')
                ->references('id')
                ->on('tests')
                ->onUpdate('no action')
                ->onDelete('cascade');
            $table->uuid('group_id');
            $table->foreign('group_id', 'fk-group-1')
                ->references('id')
                ->on('groups')
                ->onUpdate('no action')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_groups');
    }
};
