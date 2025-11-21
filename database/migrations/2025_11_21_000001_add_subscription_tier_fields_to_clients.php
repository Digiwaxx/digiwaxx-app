<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to add subscription tier and billing fields to clients table.
 *
 * Supports three subscription tiers:
 * - free: 1 upload/month, $0
 * - artist: 2 uploads/month, $20/month or $180/year
 * - label: 20 uploads/month, $149/month or $1,188/year
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Subscription tier: 'free', 'artist', 'label'
            $table->string('subscription_tier', 50)->default('free')->after('active');

            // Billing cycle: 'monthly', 'annual'
            $table->string('billing_cycle', 20)->default('monthly')->after('subscription_tier');

            // Upload limits
            $table->integer('upload_limit')->default(1)->after('billing_cycle');
            $table->integer('uploads_used_this_month')->default(0)->after('upload_limit');

            // Billing cycle tracking
            $table->date('billing_cycle_start')->nullable()->after('uploads_used_this_month');

            // Stripe integration fields
            $table->string('stripe_customer_id', 255)->nullable()->after('billing_cycle_start');
            $table->string('stripe_subscription_id', 255)->nullable()->after('stripe_customer_id');
            $table->string('stripe_price_id', 255)->nullable()->after('stripe_subscription_id');

            // Subscription status: 'active', 'canceled', 'past_due', 'trialing', 'incomplete'
            $table->string('subscription_status', 50)->default('active')->after('stripe_price_id');

            // Trial and subscription end dates
            $table->timestamp('trial_ends_at')->nullable()->after('subscription_status');
            $table->timestamp('subscription_ends_at')->nullable()->after('trial_ends_at');

            // Annual member badge display
            $table->boolean('show_annual_badge')->default(false)->after('subscription_ends_at');

            // Indexes for common queries
            $table->index('subscription_tier', 'idx_clients_subscription_tier');
            $table->index('stripe_customer_id', 'idx_clients_stripe_customer_id');
            $table->index('stripe_subscription_id', 'idx_clients_stripe_subscription_id');
            $table->index('subscription_status', 'idx_clients_subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_clients_subscription_tier');
            $table->dropIndex('idx_clients_stripe_customer_id');
            $table->dropIndex('idx_clients_stripe_subscription_id');
            $table->dropIndex('idx_clients_subscription_status');

            // Drop columns
            $table->dropColumn([
                'subscription_tier',
                'billing_cycle',
                'upload_limit',
                'uploads_used_this_month',
                'billing_cycle_start',
                'stripe_customer_id',
                'stripe_subscription_id',
                'stripe_price_id',
                'subscription_status',
                'trial_ends_at',
                'subscription_ends_at',
                'show_annual_badge',
            ]);
        });
    }
};
