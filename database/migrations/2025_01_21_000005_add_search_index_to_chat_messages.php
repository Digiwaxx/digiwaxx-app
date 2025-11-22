<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration: Add full-text search index to chat messages
 *
 * Adds MySQL FULLTEXT index for efficient message search.
 * Enables searching message content quickly.
 */
class AddSearchIndexToChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // MySQL FULLTEXT index for message search
        DB::statement('ALTER TABLE chat_messages ADD FULLTEXT search_messages(message)');

        // Add compound index for user-specific search
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->index(['senderId', 'receiverId', 'dateTime'], 'idx_conversation_search');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE chat_messages DROP INDEX search_messages');

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex('idx_conversation_search');
        });
    }
}
