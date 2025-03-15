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
        Schema::create('user_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id', 'fk-user-group-1')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->uuid('group_id')->nullable();
            $table->foreign('group_id', 'fk-user-group-2')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_groups');
    }
};
