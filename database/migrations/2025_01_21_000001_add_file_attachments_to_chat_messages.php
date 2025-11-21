<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add file attachment support to chat messages
 *
 * Adds columns for file attachments, including:
 * - attachment_type (image, audio, document, video, other)
 * - attachment_path (file storage path)
 * - attachment_name (original filename)
 * - attachment_size (file size in bytes)
 * - attachment_mime (MIME type)
 */
class AddFileAttachmentsToChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            // File attachment columns
            $table->enum('attachment_type', ['image', 'audio', 'document', 'video', 'other'])
                  ->nullable()
                  ->after('message')
                  ->comment('Type of file attachment');

            $table->string('attachment_path', 500)
                  ->nullable()
                  ->after('attachment_type')
                  ->comment('Storage path to attachment file');

            $table->string('attachment_name', 255)
                  ->nullable()
                  ->after('attachment_path')
                  ->comment('Original filename of attachment');

            $table->integer('attachment_size')
                  ->unsigned()
                  ->nullable()
                  ->after('attachment_name')
                  ->comment('File size in bytes');

            $table->string('attachment_mime', 100)
                  ->nullable()
                  ->after('attachment_size')
                  ->comment('MIME type of attachment');

            // Add index for querying messages with attachments
            $table->index('attachment_type', 'idx_attachment_type');
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
            $table->dropIndex('idx_attachment_type');
            $table->dropColumn([
                'attachment_type',
                'attachment_path',
                'attachment_name',
                'attachment_size',
                'attachment_mime'
            ]);
        });
    }
}
