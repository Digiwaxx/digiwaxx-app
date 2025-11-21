<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Social Sharing Columns to Tracks Reviews Table
 *
 * This migration adds columns to enable artists to share DJ reviews/ratings:
 * - is_shareable: Whether DJ allows their review to be shared (privacy setting)
 * - share_count: Number of times this review has been shared
 * - last_shared_at: When review was last shared
 * - created_at: When review was created (for tracking)
 */
class AddSocialSharingToTracksReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks_reviews', function (Blueprint $table) {
            // Check if columns already exist before adding
            if (!Schema::hasColumn('tracks_reviews', 'is_shareable')) {
                $table->boolean('is_shareable')->default(true)->after('additionalcomments')
                    ->comment('Whether DJ allows this review to be shared by artist');
            }

            if (!Schema::hasColumn('tracks_reviews', 'share_count')) {
                $table->integer('share_count')->default(0)->after('is_shareable')
                    ->comment('Number of times this review has been shared');
            }

            if (!Schema::hasColumn('tracks_reviews', 'last_shared_at')) {
                $table->timestamp('last_shared_at')->nullable()->after('share_count')
                    ->comment('When this review was last shared on social media');
            }

            // Add timestamps if they don't exist
            if (!Schema::hasColumn('tracks_reviews', 'created_at')) {
                $table->timestamps();
            }

            // Indexes for performance
            $table->index('is_shareable', 'idx_reviews_is_shareable');
            $table->index('share_count', 'idx_reviews_share_count');
            $table->index(['whatrate', 'is_shareable'], 'idx_reviews_rating_shareable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks_reviews', function (Blueprint $table) {
            $table->dropIndex('idx_reviews_is_shareable');
            $table->dropIndex('idx_reviews_share_count');
            $table->dropIndex('idx_reviews_rating_shareable');

            $table->dropColumn([
                'is_shareable',
                'share_count',
                'last_shared_at'
            ]);
        });
    }
}
