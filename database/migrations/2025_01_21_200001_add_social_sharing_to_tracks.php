<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Add Social Sharing Columns to Tracks Table
 *
 * This migration adds columns to enable social media sharing of tracks by DJs:
 * - is_public: Whether track can be shared publicly
 * - share_slug: Unique slug for public URL
 * - share_count: Total number of times track has been shared
 * - public_page_views: Track views on public page
 * - last_shared_at: When track was last shared
 */
class AddSocialSharingToTracks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            // Social sharing settings
            $table->boolean('is_public')->default(true)->after('id')
                ->comment('Whether track can be shared publicly');

            $table->string('share_slug', 255)->nullable()->unique()->after('is_public')
                ->comment('Unique slug for public shareable URL');

            $table->integer('share_count')->default(0)->after('share_slug')
                ->comment('Total number of times track has been shared');

            $table->integer('public_page_views')->default(0)->after('share_count')
                ->comment('Number of views on public share page');

            $table->timestamp('last_shared_at')->nullable()->after('public_page_views')
                ->comment('When track was last shared on social media');

            // Indexes for performance
            $table->index('is_public', 'idx_tracks_is_public');
            $table->index('share_slug', 'idx_tracks_share_slug');
            $table->index('share_count', 'idx_tracks_share_count');
        });

        // Generate share slugs for all existing tracks
        DB::statement("
            UPDATE tracks
            SET share_slug = CONCAT(
                LOWER(REPLACE(REPLACE(REPLACE(title, ' ', '-'), '&', 'and'), '/', '-')),
                '-',
                id
            )
            WHERE share_slug IS NULL
        ");

        // Make share_slug NOT NULL after generating values
        Schema::table('tracks', function (Blueprint $table) {
            $table->string('share_slug', 255)->nullable(false)->change();
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
            $table->dropIndex('idx_tracks_is_public');
            $table->dropIndex('idx_tracks_share_slug');
            $table->dropIndex('idx_tracks_share_count');

            $table->dropColumn([
                'is_public',
                'share_slug',
                'share_count',
                'public_page_views',
                'last_shared_at'
            ]);
        });
    }
}
