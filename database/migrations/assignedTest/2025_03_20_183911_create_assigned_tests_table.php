<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assigned_tests', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(Uuid::uuid7()->toString());
            $table->string('name');
            $table->string('frequency')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_anonymous');
            $table->boolean('late_result');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('test_status');
            $table->float('min_percentage')->nullable();

            $table->foreignUuid('template_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('subject_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignUuid('assigner_id')->constrained('users')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assigned_tests');
    }
};
