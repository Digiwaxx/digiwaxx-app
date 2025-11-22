<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Subscription;

/**
 * Controller to handle incoming Stripe webhooks.
 *
 * Handles subscription lifecycle events:
 * - customer.subscription.created
 * - customer.subscription.updated
 * - customer.subscription.deleted
 * - invoice.payment_succeeded
 * - invoice.payment_failed
 *
 * IMPORTANT: This route must be excluded from CSRF protection.
 * Add 'stripe/webhook' to the $except array in VerifyCsrfToken middleware.
 */
class StripeWebhookController extends Controller
{
    /**
     * Upload limits by subscription tier
     */
    private const UPLOAD_LIMITS = [
        'free' => 1,
        'artist' => 2,
        'label' => 20,
    ];

    /**
     * Handle incoming Stripe webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('stripe.webhook_secret') ?? env('STRIPE_WEBHOOK_SECRET');

        // Verify webhook signature
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe webhook: Invalid payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook: Invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // Log the event for debugging
        Log::info('Stripe webhook received', [
            'type' => $event->type,
            'id' => $event->id,
        ]);

        // Handle different event types
        try {
            switch ($event->type) {
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                case 'invoice.paid':
                    $this->handleInvoicePaid($event->data->object);
                    break;

                case 'customer.subscription.trial_will_end':
                    $this->handleTrialWillEnd($event->data->object);
                    break;

                default:
                    Log::info('Stripe webhook: Unhandled event type', ['type' => $event->type]);
            }
        } catch (\Exception $e) {
            Log::error('Stripe webhook handler error', [
                'type' => $event->type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Still return 200 to acknowledge receipt
            // Stripe will retry if we return 4xx/5xx
        }

        return response('Webhook handled', 200);
    }

    /**
     * Handle subscription created event
     */
    private function handleSubscriptionCreated(object $subscription): void
    {
        $clientId = $subscription->metadata->client_id ?? null;
        $tier = $subscription->metadata->tier ?? 'artist';
        $billing = $subscription->metadata->billing ?? 'monthly';

        if (!$clientId) {
            // Try to find client by Stripe customer ID
            $client = DB::table('clients')
                ->where('stripe_customer_id', $subscription->customer)
                ->first();
            $clientId = $client->id ?? null;
        }

        if (!$clientId) {
            Log::warning('Stripe webhook: Could not find client for subscription', [
                'subscription_id' => $subscription->id,
                'customer_id' => $subscription->customer,
            ]);
            return;
        }

        DB::table('clients')->where('id', $clientId)->update([
            'stripe_subscription_id' => $subscription->id,
            'stripe_price_id' => $subscription->items->data[0]->price->id ?? null,
            'subscription_tier' => $tier,
            'billing_cycle' => $billing,
            'upload_limit' => self::UPLOAD_LIMITS[$tier] ?? 1,
            'subscription_status' => $this->mapStripeStatus($subscription->status),
            'billing_cycle_start' => now(),
            'show_annual_badge' => $billing === 'annual',
        ]);

        Log::info('Subscription created', [
            'client_id' => $clientId,
            'subscription_id' => $subscription->id,
            'tier' => $tier,
        ]);
    }

    /**
     * Handle subscription updated event
     */
    private function handleSubscriptionUpdated(object $subscription): void
    {
        $client = DB::table('clients')
            ->where('stripe_subscription_id', $subscription->id)
            ->first();

        if (!$client) {
            // Try by customer ID
            $client = DB::table('clients')
                ->where('stripe_customer_id', $subscription->customer)
                ->first();
        }

        if (!$client) {
            Log::warning('Stripe webhook: Could not find client for subscription update', [
                'subscription_id' => $subscription->id,
            ]);
            return;
        }

        // Get tier from metadata or price lookup
        $tier = $subscription->metadata->tier ?? $this->getTierFromPrice($subscription->items->data[0]->price->id ?? null);
        $billing = $subscription->metadata->billing ?? $this->getBillingFromPrice($subscription->items->data[0]->price->id ?? null);

        $updateData = [
            'stripe_price_id' => $subscription->items->data[0]->price->id ?? null,
            'subscription_status' => $this->mapStripeStatus($subscription->status),
        ];

        // Only update tier/billing if we have valid values
        if ($tier) {
            $updateData['subscription_tier'] = $tier;
            $updateData['upload_limit'] = self::UPLOAD_LIMITS[$tier] ?? 1;
        }

        if ($billing) {
            $updateData['billing_cycle'] = $billing;
            $updateData['show_annual_badge'] = $billing === 'annual';
        }

        // Handle cancellation at period end
        if ($subscription->cancel_at_period_end) {
            $updateData['subscription_status'] = 'canceled';
            $updateData['subscription_ends_at'] = date('Y-m-d H:i:s', $subscription->current_period_end);
        }

        DB::table('clients')->where('id', $client->id)->update($updateData);

        Log::info('Subscription updated', [
            'client_id' => $client->id,
            'subscription_id' => $subscription->id,
            'status' => $subscription->status,
        ]);
    }

    /**
     * Handle subscription deleted (canceled) event
     */
    private function handleSubscriptionDeleted(object $subscription): void
    {
        $client = DB::table('clients')
            ->where('stripe_subscription_id', $subscription->id)
            ->first();

        if (!$client) {
            Log::warning('Stripe webhook: Could not find client for subscription deletion', [
                'subscription_id' => $subscription->id,
            ]);
            return;
        }

        // Downgrade to free tier
        DB::table('clients')->where('id', $client->id)->update([
            'subscription_tier' => 'free',
            'billing_cycle' => 'monthly',
            'upload_limit' => 1,
            'subscription_status' => 'canceled',
            'stripe_subscription_id' => null,
            'stripe_price_id' => null,
            'show_annual_badge' => false,
            'subscription_ends_at' => now(),
        ]);

        // Send cancellation email
        $this->sendCancellationEmail($client);

        Log::info('Subscription deleted', [
            'client_id' => $client->id,
            'subscription_id' => $subscription->id,
        ]);
    }

    /**
     * Handle successful invoice payment
     */
    private function handleInvoicePaymentSucceeded(object $invoice): void
    {
        if (!$invoice->subscription) {
            return; // Not a subscription invoice
        }

        $client = DB::table('clients')
            ->where('stripe_subscription_id', $invoice->subscription)
            ->first();

        if (!$client) {
            return;
        }

        // Log the payment
        $this->logPayment($client->id, $invoice, 'succeeded');

        // Reset monthly upload count on successful payment (new billing cycle)
        if ($invoice->billing_reason === 'subscription_cycle') {
            DB::table('clients')->where('id', $client->id)->update([
                'billing_cycle_start' => now(),
                'subscription_status' => 'active',
            ]);

            // Reset monthly uploads counter
            DB::table('monthly_uploads')
                ->where('client_id', $client->id)
                ->where('year', now()->year)
                ->where('month', now()->month)
                ->delete();
        }

        // Send payment confirmation email
        $this->sendPaymentSuccessEmail($client, $invoice);

        Log::info('Invoice payment succeeded', [
            'client_id' => $client->id,
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount_paid,
        ]);
    }

    /**
     * Handle failed invoice payment
     */
    private function handleInvoicePaymentFailed(object $invoice): void
    {
        if (!$invoice->subscription) {
            return;
        }

        $client = DB::table('clients')
            ->where('stripe_subscription_id', $invoice->subscription)
            ->first();

        if (!$client) {
            return;
        }

        // Log the failed payment
        $this->logPayment($client->id, $invoice, 'failed');

        // Update subscription status
        DB::table('clients')->where('id', $client->id)->update([
            'subscription_status' => 'past_due',
        ]);

        // Send payment failed email
        $this->sendPaymentFailedEmail($client, $invoice);

        Log::warning('Invoice payment failed', [
            'client_id' => $client->id,
            'invoice_id' => $invoice->id,
        ]);
    }

    /**
     * Handle invoice paid event
     */
    private function handleInvoicePaid(object $invoice): void
    {
        // Similar to payment succeeded, but can be used for additional logic
        if (!$invoice->subscription) {
            return;
        }

        $client = DB::table('clients')
            ->where('stripe_subscription_id', $invoice->subscription)
            ->first();

        if ($client) {
            DB::table('clients')->where('id', $client->id)->update([
                'subscription_status' => 'active',
            ]);
        }
    }

    /**
     * Handle trial ending soon event
     */
    private function handleTrialWillEnd(object $subscription): void
    {
        $client = DB::table('clients')
            ->where('stripe_subscription_id', $subscription->id)
            ->first();

        if ($client) {
            // Send trial ending email
            $this->sendTrialEndingEmail($client, $subscription);
        }
    }

    // =======================================================================
    // HELPER METHODS
    // =======================================================================

    /**
     * Map Stripe subscription status to our status
     */
    private function mapStripeStatus(string $stripeStatus): string
    {
        $mapping = [
            'active' => 'active',
            'trialing' => 'trialing',
            'past_due' => 'past_due',
            'canceled' => 'canceled',
            'unpaid' => 'past_due',
            'incomplete' => 'incomplete',
            'incomplete_expired' => 'canceled',
        ];

        return $mapping[$stripeStatus] ?? 'active';
    }

    /**
     * Get tier from Stripe price ID
     */
    private function getTierFromPrice(?string $priceId): ?string
    {
        if (!$priceId) {
            return null;
        }

        $prices = config('stripe.prices', []);

        foreach ($prices as $key => $id) {
            if ($id === $priceId) {
                // key is like 'artist_monthly' or 'label_annual'
                return explode('_', $key)[0] ?? null;
            }
        }

        return null;
    }

    /**
     * Get billing cycle from Stripe price ID
     */
    private function getBillingFromPrice(?string $priceId): ?string
    {
        if (!$priceId) {
            return null;
        }

        $prices = config('stripe.prices', []);

        foreach ($prices as $key => $id) {
            if ($id === $priceId) {
                // key is like 'artist_monthly' or 'label_annual'
                $parts = explode('_', $key);
                return $parts[1] ?? null;
            }
        }

        return null;
    }

    /**
     * Log payment to database
     */
    private function logPayment(int $clientId, object $invoice, string $status): void
    {
        try {
            DB::table('subscription_payments')->insert([
                'client_id' => $clientId,
                'stripe_payment_intent_id' => $invoice->payment_intent ?? null,
                'stripe_invoice_id' => $invoice->id,
                'stripe_subscription_id' => $invoice->subscription,
                'subscription_tier' => $this->getTierFromPrice($invoice->lines->data[0]->price->id ?? null) ?? 'unknown',
                'billing_cycle' => $this->getBillingFromPrice($invoice->lines->data[0]->price->id ?? null) ?? 'monthly',
                'amount_cents' => $invoice->amount_paid ?? $invoice->amount_due ?? 0,
                'currency' => $invoice->currency ?? 'usd',
                'status' => $status,
                'period_start' => isset($invoice->period_start) ? date('Y-m-d H:i:s', $invoice->period_start) : null,
                'period_end' => isset($invoice->period_end) ? date('Y-m-d H:i:s', $invoice->period_end) : null,
                'paid_at' => $status === 'succeeded' ? now() : null,
                'failure_reason' => $status === 'failed' ? ($invoice->last_finalization_error->message ?? 'Payment failed') : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log payment', [
                'error' => $e->getMessage(),
                'client_id' => $clientId,
                'invoice_id' => $invoice->id,
            ]);
        }
    }

    /**
     * Send payment success email
     */
    private function sendPaymentSuccessEmail(object $client, object $invoice): void
    {
        try {
            $email = urldecode($client->email ?? '');
            $name = urldecode($client->name ?? '');
            $amount = number_format(($invoice->amount_paid ?? 0) / 100, 2);

            if (empty($email)) {
                return;
            }

            $data = [
                'emailId' => $email,
                'name' => $name,
                'amount' => $amount,
                'currency' => strtoupper($invoice->currency ?? 'USD'),
            ];

            Mail::send('mails.subscriptionPaymentSuccess', ['data' => $data], function ($message) use ($data) {
                $message->to($data['emailId']);
                $message->subject('Payment Successful - Digiwaxx Subscription');
                $message->from('business@digiwaxx.com', 'Digiwaxx');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send payment success email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send payment failed email
     */
    private function sendPaymentFailedEmail(object $client, object $invoice): void
    {
        try {
            $email = urldecode($client->email ?? '');
            $name = urldecode($client->name ?? '');

            if (empty($email)) {
                return;
            }

            $data = [
                'emailId' => $email,
                'name' => $name,
                'updatePaymentUrl' => url('/billing-portal'),
            ];

            Mail::send('mails.subscriptionPaymentFailed', ['data' => $data], function ($message) use ($data) {
                $message->to($data['emailId']);
                $message->subject('Payment Failed - Action Required');
                $message->from('business@digiwaxx.com', 'Digiwaxx');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send payment failed email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send subscription cancellation email
     */
    private function sendCancellationEmail(object $client): void
    {
        try {
            $email = urldecode($client->email ?? '');
            $name = urldecode($client->name ?? '');

            if (empty($email)) {
                return;
            }

            $data = [
                'emailId' => $email,
                'name' => $name,
                'resubscribeUrl' => url('/pricing'),
            ];

            Mail::send('mails.subscriptionCanceled', ['data' => $data], function ($message) use ($data) {
                $message->to($data['emailId']);
                $message->subject('Subscription Canceled - Digiwaxx');
                $message->from('business@digiwaxx.com', 'Digiwaxx');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send cancellation email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Send trial ending email
     */
    private function sendTrialEndingEmail(object $client, object $subscription): void
    {
        try {
            $email = urldecode($client->email ?? '');
            $name = urldecode($client->name ?? '');

            if (empty($email)) {
                return;
            }

            $trialEnds = date('F j, Y', $subscription->trial_end);

            $data = [
                'emailId' => $email,
                'name' => $name,
                'trialEndsDate' => $trialEnds,
                'manageUrl' => url('/subscription'),
            ];

            Mail::send('mails.trialEnding', ['data' => $data], function ($message) use ($data) {
                $message->to($data['emailId']);
                $message->subject('Your Trial is Ending Soon - Digiwaxx');
                $message->from('business@digiwaxx.com', 'Digiwaxx');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send trial ending email', ['error' => $e->getMessage()]);
        }
    }
}
