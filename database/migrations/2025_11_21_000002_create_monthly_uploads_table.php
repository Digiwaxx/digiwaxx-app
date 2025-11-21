<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create monthly_uploads table for tracking upload counts per user per month.
 *
 * This table tracks how many songs each client uploads each month,
 * allowing for accurate enforcement of subscription tier upload limits.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monthly_uploads', function (Blueprint $table) {
            $table->id();

            // Client reference (foreign key to clients table)
            $table->unsignedBigInteger('client_id');

            // Year and month for tracking (allows historical data)
            $table->integer('year');
            $table->tinyInteger('month'); // 1-12

            // Count of uploads in this month
            $table->integer('uploads_count')->default(0);

            // Timestamps
            $table->timestamps();

            // Unique constraint: one record per client per month
            $table->unique(['client_id', 'year', 'month'], 'unique_client_month');

            // Indexes for common queries
            $table->index('client_id', 'idx_monthly_uploads_client');
            $table->index(['year', 'month'], 'idx_monthly_uploads_year_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_uploads');
    }
};
