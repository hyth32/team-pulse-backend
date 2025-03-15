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
        Schema::create('user_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id', 'fk-user-test-1')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->uuid('test_id')->nullable();
            $table->foreign('test_id', 'fk-user-test-2')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->uuid('assignee_id');
            $table->foreign('assignee_id', 'fk-user-assignee-1')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('user_tests');
    }
};
