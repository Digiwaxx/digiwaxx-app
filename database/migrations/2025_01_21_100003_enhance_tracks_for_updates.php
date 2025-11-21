<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Enhance Tracks Table for Updates/Edits
 *
 * Allow clients to submit updates to already-published tracks.
 * All updates require ADMIN approval before going live.
 *
 * EXISTING FIELDS (DO NOT MODIFY):
 * - status (varchar) - 'publish', 'draft', etc.
 * - approved (int) - 1=approved, 0=pending
 * - active (int) - 1=visible, 0=hidden
 *
 * NEW FIELDS ADDED:
 * - has_pending_update - Flag if update is waiting for approval
 * - update_submitted_at - When update was submitted
 * - last_reviewed_at - When last admin review happened
 * - last_reviewed_by - Which admin last reviewed
 */
class EnhanceTracksForUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            // Pending update tracking
            $table->boolean('has_pending_update')
                  ->default(false)
                  ->after('approved')
                  ->comment('TRUE if client submitted an update awaiting admin approval');

            $table->timestamp('update_submitted_at')
                  ->nullable()
                  ->after('has_pending_update')
                  ->comment('When client submitted update for review');

            // Review tracking
            $table->timestamp('last_reviewed_at')
                  ->nullable()
                  ->after('update_submitted_at')
                  ->comment('When admin last reviewed this track');

            $table->unsignedBigInteger('last_reviewed_by')
                  ->nullable()
                  ->after('last_reviewed_at')
                  ->comment('Admin who last reviewed (FK to admins)');

            // Indexes
            $table->index('has_pending_update', 'idx_tracks_pending_update');
            $table->index('update_submitted_at', 'idx_tracks_update_submitted');
            $table->index(['has_pending_update', 'update_submitted_at'], 'idx_tracks_pending_queue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropIndex('idx_tracks_pending_update');
            $table->dropIndex('idx_tracks_update_submitted');
            $table->dropIndex('idx_tracks_pending_queue');

            $table->dropColumn([
                'has_pending_update',
                'update_submitted_at',
                'last_reviewed_at',
                'last_reviewed_by'
            ]);
        });
    }
}
