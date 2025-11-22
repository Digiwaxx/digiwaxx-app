<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create user online status tracking table
 *
 * Tracks online/offline status and last seen information.
 * Updated via heartbeat mechanism (every 30 seconds).
 * Composite primary key for efficient lookups.
 */
class CreateUserOnlineStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_online_status', function (Blueprint $table) {
            $table->id('id');

            // User identification
            $table->tinyInteger('userType')->comment('1=Client, 2=Member');
            $table->integer('userId')->unsigned();

            // Online status
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamp('last_activity_at')->useCurrent();

            // Session information
            $table->string('session_id', 100)->nullable()->comment('Browser session ID');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            // Timestamps
            $table->timestamps();

            // Composite unique key (one record per user)
            $table->unique(['userType', 'userId'], 'unique_user_status');

            // Indexes for performance
            $table->index('is_online', 'idx_online');
            $table->index('last_seen_at', 'idx_last_seen');
            $table->index(['userType', 'userId', 'is_online'], 'idx_user_online');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_online_status');
    }
}
