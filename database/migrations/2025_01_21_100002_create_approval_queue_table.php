<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Approval Queue Table
 *
 * Central queue for ALL admin approvals:
 * - New track uploads (from tracks_submitted)
 * - Updates to existing tracks (metadata, audio, artwork)
 * - Track deletions
 * - Version additions
 *
 * IMPORTANT: ADMIN-ONLY APPROVAL
 * - Clients can submit, edit, resubmit
 * - Only admins can approve, reject, request changes
 * - No self-approval under any circumstances
 */
class CreateApprovalQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_queue', function (Blueprint $table) {
            $table->id();

            // What's being approved
            $table->enum('action_type', [
                'new_upload',           // New track from client
                'replace_audio',        // Replace existing audio file
                'update_metadata',      // Edit track metadata
                'update_artwork',       // Replace cover art
                'add_version',          // Add new audio version
                'delete_track'          // Request to delete track
            ])->comment('Type of action requiring approval');

            // References
            $table->unsignedBigInteger('track_id')
                  ->nullable()
                  ->comment('ID in tracks table (if updating existing track)');

            $table->unsignedBigInteger('track_submitted_id')
                  ->nullable()
                  ->comment('ID in tracks_submitted table (if new upload)');

            $table->unsignedBigInteger('client_id')
                  ->comment('Client who submitted (FK to clients)');

            // What changed (JSON for flexibility)
            $table->json('old_data')
                  ->nullable()
                  ->comment('Original values (before change) - JSON encoded');

            $table->json('new_data')
                  ->nullable()
                  ->comment('New values (after change) - JSON encoded');

            // Approval status
            $table->enum('status', ['pending', 'approved', 'rejected', 'revision_requested'])
                  ->default('pending')
                  ->comment('Current status of approval request');

            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])
                  ->default('normal')
                  ->comment('Priority level for review');

            // Timestamps
            $table->timestamp('submitted_at')
                  ->useCurrent()
                  ->comment('When client submitted');

            $table->timestamp('reviewed_at')
                  ->nullable()
                  ->comment('When admin reviewed');

            // Admin actions (ADMIN-ONLY)
            $table->unsignedBigInteger('reviewed_by')
                  ->nullable()
                  ->comment('Admin who reviewed (FK to admins) - ADMIN-ONLY');

            $table->text('admin_notes')
                  ->nullable()
                  ->comment('Internal admin notes (NOT visible to client)');

            $table->text('rejection_reason')
                  ->nullable()
                  ->comment('Why rejected (visible to client)');

            $table->text('client_message')
                  ->nullable()
                  ->comment('Message from admin to client (visible to client)');

            // Metadata
            $table->boolean('notified')
                  ->default(false)
                  ->comment('Whether client was notified of decision');

            $table->timestamps();

            // Indexes for performance
            $table->index('status', 'idx_approval_queue_status');
            $table->index('action_type', 'idx_approval_queue_action');
            $table->index('client_id', 'idx_approval_queue_client');
            $table->index('track_id', 'idx_approval_queue_track');
            $table->index('track_submitted_id', 'idx_approval_queue_submitted');
            $table->index('submitted_at', 'idx_approval_queue_submitted_at');
            $table->index('priority', 'idx_approval_queue_priority');
            $table->index(['status', 'priority', 'submitted_at'], 'idx_approval_queue_review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_queue');
    }
}
