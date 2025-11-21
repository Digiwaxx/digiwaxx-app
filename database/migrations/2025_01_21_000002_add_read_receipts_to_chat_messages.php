<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add read receipt tracking to chat messages
 *
 * Enhances the existing 'unread' flag system with timestamps:
 * - read_at: When the message was read by the receiver
 * - delivered_at: When the message was delivered to the receiver
 */
class AddReadReceiptsToChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // Read receipt timestamps
            $table->timestamp('delivered_at')
                  ->nullable()
                  ->after('unread')
                  ->comment('When message was delivered to receiver');

            $table->timestamp('read_at')
                  ->nullable()
                  ->after('delivered_at')
                  ->comment('When message was read by receiver');

            // Add indexes for performance
            $table->index('read_at', 'idx_read_at');
            $table->index(['receiverId', 'read_at'], 'idx_receiver_read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('idx_read_at');
            $table->dropIndex('idx_receiver_read');
            $table->dropColumn(['delivered_at', 'read_at']);
        });
    }
}
