<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create subscription_payments table for tracking subscription payment history.
 *
 * This table stores a history of all subscription-related payments,
 * including successful payments, failed payments, and refunds.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();

            // Client reference
            $table->unsignedBigInteger('client_id');

            // Stripe identifiers
            $table->string('stripe_payment_intent_id', 255)->nullable();
            $table->string('stripe_invoice_id', 255)->nullable();
            $table->string('stripe_subscription_id', 255)->nullable();

            // Payment details
            $table->string('subscription_tier', 50); // 'artist', 'label'
            $table->string('billing_cycle', 20); // 'monthly', 'annual'
            $table->integer('amount_cents'); // Amount in cents
            $table->string('currency', 10)->default('usd');

            // Payment status: 'succeeded', 'failed', 'pending', 'refunded'
            $table->string('status', 50)->default('pending');

            // Payment metadata
            $table->string('payment_method_type', 50)->nullable(); // 'card', etc.
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_brand', 50)->nullable(); // 'visa', 'mastercard', etc.

            // Failure tracking
            $table->text('failure_reason')->nullable();
            $table->string('failure_code', 100)->nullable();

            // Period covered by this payment
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();

            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('client_id', 'idx_subscription_payments_client');
            $table->index('stripe_payment_intent_id', 'idx_subscription_payments_payment_intent');
            $table->index('stripe_invoice_id', 'idx_subscription_payments_invoice');
            $table->index('stripe_subscription_id', 'idx_subscription_payments_subscription');
            $table->index('status', 'idx_subscription_payments_status');
            $table->index('paid_at', 'idx_subscription_payments_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
