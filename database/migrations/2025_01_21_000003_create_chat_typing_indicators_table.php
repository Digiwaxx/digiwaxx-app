<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create typing indicators table
 *
 * Tracks when users are typing in conversations.
 * Records expire automatically after 5 seconds of inactivity.
 * Uses composite primary key for efficient lookups.
 */
class CreateChatTypingIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_typing_indicators', function (Blueprint $table) {
            $table->id('id');

            // User who is typing
            $table->tinyInteger('userType')->comment('1=Client, 2=Member');
            $table->integer('userId')->unsigned();

            // User being typed to (conversation partner)
            $table->tinyInteger('recipientType')->comment('1=Client, 2=Member');
            $table->integer('recipientId')->unsigned();

            // When typing started
            $table->timestamp('typing_at')->useCurrent();

            // Auto-expire after 5 seconds
            $table->timestamp('expires_at')->nullable();

            // Composite unique key to prevent duplicates
            $table->unique(['userType', 'userId', 'recipientType', 'recipientId'], 'unique_typing_user');

            // Index for querying active typing indicators
            $table->index(['recipientType', 'recipientId', 'expires_at'], 'idx_recipient_typing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_typing_indicators');
    }
}
