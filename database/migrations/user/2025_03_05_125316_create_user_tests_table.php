<?php

use App\Enums\Test\TestCompletionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_tests', function (Blueprint $table) {
            $table->primary(['user_id', 'test_id']);
            $table->uuid('user_id');
            $table->foreign('user_id', 'fk-user-test-1')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->uuid('test_id');
            $table->foreign('test_id', 'fk-user-test-2')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->uuid('assigner_id');
            $table->foreign('assigner_id', 'fk-test-assigner-1')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->integer('completion_status')->default(TestCompletionStatus::NotPassed->value());
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
