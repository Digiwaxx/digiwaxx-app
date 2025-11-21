<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Share Page Views Table
 *
 * This table tracks views of public shareable pages:
 * - Track public pages (when someone clicks a DJ's share link)
 * - Review public pages (when someone clicks an artist's review share link)
 *
 * Helps measure virality and reach of shared content.
 */
class CreateSharePageViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_page_views', function (Blueprint $table) {
            $table->id();

            // What was viewed (polymorphic)
            $table->string('viewable_type', 50)->comment('Type: track or review');
            $table->unsignedBigInteger('viewable_id')->comment('ID of track or review');

            // Visitor tracking
            $table->string('ip_address', 45)->nullable()->comment('Visitor IP address');
            $table->text('user_agent')->nullable()->comment('Browser user agent');
            $table->text('referrer')->nullable()->comment('Where visitor came from');

            // UTM tracking (from share link)
            $table->string('utm_source', 100)->nullable()->comment('UTM source (e.g., facebook, twitter)');
            $table->string('utm_medium', 100)->nullable()->comment('UTM medium (e.g., social, share)');
            $table->string('utm_campaign', 100)->nullable()->comment('UTM campaign name');

            // Geographic data (optional - can add GeoIP lookup)
            $table->string('country_code', 2)->nullable()->comment('2-letter country code');
            $table->string('city', 100)->nullable()->comment('City name');

            // Device info
            $table->string('device_type', 50)->nullable()->comment('Device: desktop, mobile, tablet');
            $table->string('browser', 100)->nullable()->comment('Browser name');
            $table->string('os', 100)->nullable()->comment('Operating system');

            // Engagement tracking
            $table->integer('time_on_page')->nullable()->comment('Seconds spent on page');
            $table->boolean('played_audio')->default(false)->comment('Did visitor play audio preview');

            // Timestamps
            $table->timestamp('viewed_at')->useCurrent()->comment('When page was viewed');

            // Indexes
            $table->index(['viewable_type', 'viewable_id'], 'idx_viewable');
            $table->index('viewed_at', 'idx_viewed_at');
            $table->index('utm_source', 'idx_utm_source');
            $table->index(['viewable_type', 'viewable_id', 'viewed_at'], 'idx_viewable_date');
            $table->index('country_code', 'idx_country');
            $table->index('device_type', 'idx_device_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('share_page_views');
    }
}
