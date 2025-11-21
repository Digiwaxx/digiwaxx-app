<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration: Enhance Tracks Submitted Approval System
 *
 * EXISTING FIELDS (DO NOT MODIFY):
 * - approved (int) - 0=pending, 1=approved (keep for backwards compatibility)
 *
 * NEW FIELDS ADDED:
 * - status (enum) - More detailed status tracking
 * - submitted_at - When client submitted for review
 * - reviewed_at - When admin reviewed
 * - reviewed_by - Admin who reviewed (FK to admins table)
 * - rejection_reason - Why track was rejected
 * - admin_notes - Internal admin notes (not visible to client)
 * - client_message - Message from admin to client
 * - revision_requested - Flag for "request changes" workflow
 */
class EnhanceTracksSubmittedApprovalSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks_submitted', function (Blueprint $table) {
            // Enhanced status field (complements existing 'approved' field)
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'revision_requested'])
                  ->default('pending')
                  ->after('approved')
                  ->comment('Enhanced status: draft, pending, approved, rejected, revision_requested');

            // Timestamps for approval workflow
            $table->timestamp('submitted_at')
                  ->nullable()
                  ->after('status')
                  ->comment('When client submitted for admin review');

            $table->timestamp('reviewed_at')
                  ->nullable()
                  ->after('submitted_at')
                  ->comment('When admin reviewed the submission');

            // Admin tracking
            $table->unsignedBigInteger('reviewed_by')
                  ->nullable()
                  ->after('reviewed_at')
                  ->comment('Admin user ID who reviewed (FK to admins)');

            // Feedback fields
            $table->text('rejection_reason')
                  ->nullable()
                  ->after('reviewed_by')
                  ->comment('Why track was rejected (visible to client)');

            $table->text('admin_notes')
                  ->nullable()
                  ->after('rejection_reason')
                  ->comment('Internal admin notes (NOT visible to client)');

            $table->text('client_message')
                  ->nullable()
                  ->after('admin_notes')
                  ->comment('Message from admin to client (visible to client)');

            // Revision tracking
            $table->boolean('revision_requested')
                  ->default(false)
                  ->after('client_message')
                  ->comment('TRUE if admin requested changes');

            $table->integer('revision_count')
                  ->default(0)
                  ->after('revision_requested')
                  ->comment('Number of times resubmitted after revision requests');

            // Indexes for performance
            $table->index('status', 'idx_tracks_submitted_status');
            $table->index('submitted_at', 'idx_tracks_submitted_submitted');
            $table->index('reviewed_by', 'idx_tracks_submitted_reviewer');
            $table->index(['client', 'status'], 'idx_tracks_submitted_client_status');
        });

        // Update existing records to have status field match approved field
        DB::statement("UPDATE tracks_submitted SET status = 'approved' WHERE approved = 1");
        DB::statement("UPDATE tracks_submitted SET status = 'pending' WHERE approved = 0 AND status != 'approved'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks_submitted', function (Blueprint $table) {
            $table->dropIndex('idx_tracks_submitted_status');
            $table->dropIndex('idx_tracks_submitted_submitted');
            $table->dropIndex('idx_tracks_submitted_reviewer');
            $table->dropIndex('idx_tracks_submitted_client_status');

            $table->dropColumn([
                'status',
                'submitted_at',
                'reviewed_at',
                'reviewed_by',
                'rejection_reason',
                'admin_notes',
                'client_message',
                'revision_requested',
                'revision_count'
            ]);
        });
    }
}
