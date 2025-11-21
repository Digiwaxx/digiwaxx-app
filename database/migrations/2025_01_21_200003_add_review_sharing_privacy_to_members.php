<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Review Sharing Privacy Setting to Members Table
 *
 * This migration adds a privacy setting that allows DJs (members) to control
 * whether artists can share their reviews on social media.
 *
 * - allow_review_sharing: DJ privacy setting (default TRUE)
 * - If FALSE, artists cannot share this DJ's reviews publicly
 */
class AddReviewSharingPrivacyToMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            // Privacy setting for review sharing
            $table->boolean('allow_review_sharing')->default(true)->after('id')
                ->comment('Whether artists can share this DJs reviews on social media');

            // Track when setting was last updated
            $table->timestamp('review_sharing_updated_at')->nullable()->after('allow_review_sharing')
                ->comment('When review sharing setting was last changed');

            // Index for filtering queries
            $table->index('allow_review_sharing', 'idx_members_review_sharing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropIndex('idx_members_review_sharing');

            $table->dropColumn([
                'allow_review_sharing',
                'review_sharing_updated_at'
            ]);
        });
    }
}
