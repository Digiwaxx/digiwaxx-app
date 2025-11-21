<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Add review notification token for secure unsubscribe links
            if (!Schema::hasColumn('clients', 'review_notification_token')) {
                $table->string('review_notification_token', 64)->unique()->nullable()->after('trackReviewEmailsActivated');
            }

            // Add granular email preference columns
            if (!Schema::hasColumn('clients', 'email_weekly_digest')) {
                $table->boolean('email_weekly_digest')->default(true)->after('review_notification_token');
            }

            if (!Schema::hasColumn('clients', 'email_milestones')) {
                $table->boolean('email_milestones')->default(true)->after('email_weekly_digest');
            }

            if (!Schema::hasColumn('clients', 'email_newsletter')) {
                $table->boolean('email_newsletter')->default(true)->after('email_milestones');
            }

            // Add timestamp for when preferences were last updated
            if (!Schema::hasColumn('clients', 'email_preferences_updated_at')) {
                $table->timestamp('email_preferences_updated_at')->nullable()->after('email_newsletter');
            }

            // Add index on email for faster lookups
            if (!Schema::hasIndex('clients', 'clients_email_index')) {
                $table->index('email', 'clients_email_index');
            }
        });

        // Create table for generated reports history
        if (!Schema::hasTable('generated_reports')) {
            Schema::create('generated_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('track_id')->nullable();
                $table->string('report_type', 50); // validation, demand, regional, format, full
                $table->string('format', 10); // pdf, csv
                $table->date('date_range_start')->nullable();
                $table->date('date_range_end')->nullable();
                $table->string('file_path', 255);
                $table->timestamp('generated_at')->useCurrent();
                $table->timestamp('expires_at')->nullable();
                $table->integer('download_count')->default(0);
                $table->timestamps();

                // Indexes for performance
                $table->index('client_id');
                $table->index('track_id');
                $table->index('generated_at');
                $table->index('expires_at');
            });
        }

        // Create table for email notification logs
        if (!Schema::hasTable('email_notification_logs')) {
            Schema::create('email_notification_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id');
                $table->unsignedBigInteger('track_id')->nullable();
                $table->unsignedBigInteger('review_id')->nullable();
                $table->string('notification_type', 50); // review, weekly_digest, milestone, newsletter
                $table->string('status', 20); // sent, failed, bounced, opened, clicked
                $table->string('recipient_email', 255);
                $table->text('error_message')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('opened_at')->nullable();
                $table->timestamp('clicked_at')->nullable();
                $table->timestamps();

                // Indexes
                $table->index('client_id');
                $table->index('track_id');
                $table->index('review_id');
                $table->index('notification_type');
                $table->index('status');
                $table->index('sent_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'review_notification_token',
                'email_weekly_digest',
                'email_milestones',
                'email_newsletter',
                'email_preferences_updated_at'
            ]);

            $table->dropIndex('clients_email_index');
        });

        Schema::dropIfExists('generated_reports');
        Schema::dropIfExists('email_notification_logs');
    }
};
