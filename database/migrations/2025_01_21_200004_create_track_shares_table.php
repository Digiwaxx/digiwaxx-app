<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Track Shares Table
 *
 * This table tracks all social media sharing activity for:
 * - Tracks shared by DJs
 * - Reviews shared by artists
 *
 * Enables analytics and reporting on sharing behavior.
 */
class CreateTrackSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_shares', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship (track or review)
            $table->string('shareable_type', 50)->comment('Type: track or review');
            $table->unsignedBigInteger('shareable_id')->comment('ID of track or review');

            // Platform where shared
            $table->enum('platform', ['facebook', 'twitter', 'instagram', 'tiktok', 'linkedin', 'whatsapp', 'copy_link', 'download_image'])
                ->comment('Social media platform used for sharing');

            // Who shared it
            $table->unsignedBigInteger('user_id')->nullable()->comment('User who shared (member or client)');
            $table->string('user_type', 50)->nullable()->comment('Type: member or client');

            // Tracking data
            $table->string('ip_address', 45)->nullable()->comment('IP address of sharer');
            $table->text('user_agent')->nullable()->comment('Browser user agent');
            $table->text('referrer')->nullable()->comment('HTTP referrer if available');

            // Custom tracking
            $table->string('utm_source', 100)->nullable()->comment('UTM tracking source');
            $table->string('utm_medium', 100)->nullable()->comment('UTM tracking medium');
            $table->string('utm_campaign', 100)->nullable()->comment('UTM tracking campaign');

            // Share metadata
            $table->text('share_text')->nullable()->comment('Text/caption used when sharing');
            $table->json('share_metadata')->nullable()->comment('Additional metadata about share');

            // Timestamps
            $table->timestamp('shared_at')->useCurrent()->comment('When share occurred');

            // Indexes for analytics queries
            $table->index(['shareable_type', 'shareable_id'], 'idx_shareable');
            $table->index('platform', 'idx_platform');
            $table->index(['user_id', 'user_type'], 'idx_user');
            $table->index('shared_at', 'idx_shared_at');
            $table->index(['platform', 'shared_at'], 'idx_platform_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track_shares');
    }
}
