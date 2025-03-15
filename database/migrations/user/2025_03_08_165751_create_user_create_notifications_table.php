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
        Schema::create('user_create_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('is_notified')->default(0);
            $table->uuid('user_id');
            $table->foreign('user_id', 'fk-notification-user-1')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('user_create_notifications');
    }
};
