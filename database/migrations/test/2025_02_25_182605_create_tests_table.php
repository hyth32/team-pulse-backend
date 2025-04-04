<?php

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
        Schema::create('tests', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Uuid::uuid7()->toString());
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('frequency')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->uuid('author_id')->nullable();
            $table->foreign('author_id', 'fk-test-author-1')->references('id')->on('users')->onUpdate('no action')->onDelete('no action');
            $table->uuid('subject_id')->nullable();
            $table->foreign('subject_id', 'fk-test-subject-1')->references('id')->on('users')->onUpdate('no action')->onDelete('no action');
            $table->integer('test_status');
            $table->boolean('is_anonymous')->default(false);

            $table->softDeletes();
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
