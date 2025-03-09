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
        Schema::create('tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->uuid('periodicity')->nullable();
            $table->foreign('periodicity', 'fk-test-periodicity-1')->references('id')->on('test_periodicities')->onUpdate('no action')->onDelete('set null');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('assignee_id')->nullable();
            $table->integer('status')->default(1);
            $table->foreign('assignee_id', 'fk-test-assignee-1')->references('id')->on('users')->onUpdate('no action')->onDelete('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
