<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create Approval Notifications Table
 *
 * Tracks all notifications sent regarding track approvals:
 * - Client notifications (approved, rejected, revision requested)
 * - Admin notifications (new submission, overdue review)
 *
 * Supports both email and in-app notifications.
 */
class CreateApprovalNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_notifications', function (Blueprint $table) {
            $table->id();

            // Who receives the notification
            $table->enum('recipient_type', ['client', 'admin'])
                  ->comment('Who receives: client or admin');

            $table->unsignedBigInteger('recipient_id')
                  ->comment('Client ID or Admin ID');

            // What it's about
            $table->unsignedBigInteger('approval_queue_id')
                  ->nullable()
                  ->comment('FK to approval_queue');

            $table->unsignedBigInteger('track_id')
                  ->nullable()
                  ->comment('FK to tracks (if applicable)');

            $table->unsignedBigInteger('track_submitted_id')
                  ->nullable()
                  ->comment('FK to tracks_submitted (if applicable)');

            // Notification content
            $table->enum('notification_type', [
                'submission_confirmed',      // Client: Track submitted
                'approved',                  // Client: Track approved
                'rejected',                  // Client: Track rejected
                'revision_requested',        // Client: Changes requested
                'new_submission',            // Admin: New track to review
                'overdue_review',            // Admin: Review is overdue
                'daily_digest'               // Admin: Daily summary
            ])->comment('Type of notification');

            $table->string('subject', 255)
                  ->comment('Email subject line');

            $table->text('message')
                  ->comment('Notification message content');

            // Delivery status
            $table->enum('delivery_method', ['email', 'in_app', 'both'])
                  ->default('both')
                  ->comment('How notification was delivered');

            $table->boolean('email_sent')
                  ->default(false)
                  ->comment('Whether email was successfully sent');

            $table->timestamp('email_sent_at')
                  ->nullable()
                  ->comment('When email was sent');

            $table->boolean('read')
                  ->default(false)
                  ->comment('Whether in-app notification was read');

            $table->timestamp('read_at')
                  ->nullable()
                  ->comment('When notification was read');

            $table->timestamps();

            // Indexes
            $table->index(['recipient_type', 'recipient_id'], 'idx_notif_recipient');
            $table->index('approval_queue_id', 'idx_notif_queue');
            $table->index('notification_type', 'idx_notif_type');
            $table->index('read', 'idx_notif_read');
            $table->index('created_at', 'idx_notif_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_notifications');
    }
}
