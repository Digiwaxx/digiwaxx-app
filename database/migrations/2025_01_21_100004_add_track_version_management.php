<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Add Track Version Management
 *
 * Allows clients to:
 * - Upload new versions of existing tracks (remasters, remixes, edits)
 * - Link child versions to parent track
 * - Track version history
 *
 * All new versions require ADMIN approval.
 */
class AddTrackVersionManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Enhance tracks table
        Schema::table('tracks', function (Blueprint $table) {
            // Version tracking
            $table->boolean('is_version')
                  ->default(false)
                  ->after('approved')
                  ->comment('TRUE if this is a version of another track');

            $table->unsignedBigInteger('parent_track_id')
                  ->nullable()
                  ->after('is_version')
                  ->comment('FK to parent track if this is a version');

            $table->string('version_type', 50)
                  ->nullable()
                  ->after('parent_track_id')
                  ->comment('Type: remaster, remix, edit, live, acoustic, etc.');

            $table->integer('version_number')
                  ->default(1)
                  ->after('version_type')
                  ->comment('Version number (incremental)');

            // Indexes
            $table->index('parent_track_id', 'idx_tracks_parent');
            $table->index('is_version', 'idx_tracks_is_version');
            $table->index(['parent_track_id', 'version_number'], 'idx_tracks_version');
        });

        // Enhance tracks_submitted table (for version submissions)
        Schema::table('tracks_submitted', function (Blueprint $table) {
            // Version tracking
            $table->boolean('is_version')
                  ->default(false)
                  ->after('approved')
                  ->comment('TRUE if this is a new version of existing track');

            $table->unsignedBigInteger('parent_track_id')
                  ->nullable()
                  ->after('is_version')
                  ->comment('FK to parent track in tracks table');

            $table->string('version_type', 50)
                  ->nullable()
                  ->after('parent_track_id')
                  ->comment('Type: remaster, remix, edit, etc.');

            // Indexes
            $table->index('parent_track_id', 'idx_sub_tracks_parent');
            $table->index('is_version', 'idx_sub_tracks_is_version');
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
            $table->dropIndex('idx_tracks_parent');
            $table->dropIndex('idx_tracks_is_version');
            $table->dropIndex('idx_tracks_version');

            $table->dropColumn([
                'is_version',
                'parent_track_id',
                'version_type',
                'version_number'
            ]);
        });

        Schema::table('tracks_submitted', function (Blueprint $table) {
            $table->dropIndex('idx_sub_tracks_parent');
            $table->dropIndex('idx_sub_tracks_is_version');

            $table->dropColumn([
                'is_version',
                'parent_track_id',
                'version_type'
            ]);
        });
    }
}
