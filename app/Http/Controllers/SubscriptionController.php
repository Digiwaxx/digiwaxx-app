<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session as StripeSession;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Subscription;

class SubscriptionController extends Controller
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
     * Initialize Stripe API
     */
    private function initStripe(): void
    {
        $stripeSecret = config('stripe.secret') ?? env('STRIPE_SECRET');

        if (empty($stripeSecret)) {
            throw new \Exception('Stripe API key not configured');
        }

        Stripe::setApiKey($stripeSecret);
    }

    /**
     * Get the current authenticated client
     */
    private function getAuthenticatedClient(): ?object
    {
        $clientId = Session::get('clientId');

        if (!$clientId) {
            return null;
        }

        return DB::table('clients')->where('id', $clientId)->first();
    }

    /**
     * Display pricing page with all subscription tiers
     */
    public function pricing(Request $request)
    {
        $client = $this->getAuthenticatedClient();
        $currentTier = $client->subscription_tier ?? 'free';
        $currentBilling = $client->billing_cycle ?? 'monthly';

        // Get tier configuration from config
        $tiers = config('stripe.tiers');

        $data = [
            'pageTitle' => 'Digiwaxx - Pricing Plans',
            'tiers' => $tiers,
            'currentTier' => $currentTier,
            'currentBilling' => $currentBilling,
            'isLoggedIn' => !empty($client),
            'stripePublishableKey' => config('stripe.publishable') ?? env('STRIPE_PUBLISHABLE'),
        ];

        return view('subscription.pricing', $data);
    }

    /**
     * Initiate Stripe Checkout for subscription
     */
    public function checkout(Request $request, string $tier, string $billing)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            return redirect()->route('login')->with('error', 'Please log in to subscribe');
        }

        // Handle free plan - no payment needed
        if ($tier === 'free') {
            return $this->assignFreePlan($client);
        }

        // Validate tier and billing
        if (!in_array($tier, ['artist', 'label']) || !in_array($billing, ['monthly', 'annual'])) {
            return redirect()->route('pricing')->with('error', 'Invalid plan selection');
        }

        try {
            $this->initStripe();

            // Get the Stripe Price ID
            $priceId = $this->getPriceId($tier, $billing);

            if (!$priceId || $priceId === 'price_ARTIST_MONTHLY_PLACEHOLDER') {
                Log::error('Stripe price ID not configured', ['tier' => $tier, 'billing' => $billing]);
                return redirect()->route('pricing')->with('error', 'Subscription plans are not configured yet. Please contact support.');
            }

            // Create or retrieve Stripe customer
            $stripeCustomerId = $this->getOrCreateStripeCustomer($client);

            // Create Stripe Checkout Session
            $checkoutSession = StripeSession::create([
                'customer' => $stripeCustomerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('subscribe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscribe.cancel'),
                'metadata' => [
                    'client_id' => $client->id,
                    'tier' => $tier,
                    'billing' => $billing,
                ],
                'subscription_data' => [
                    'metadata' => [
                        'client_id' => $client->id,
                        'tier' => $tier,
                        'billing' => $billing,
                    ],
                ],
            ]);

            // Redirect to Stripe Checkout
            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            Log::error('Stripe checkout error', [
                'error' => $e->getMessage(),
                'client_id' => $client->id,
                'tier' => $tier,
                'billing' => $billing,
            ]);

            return redirect()->route('pricing')->with('error', 'Unable to process subscription. Please try again.');
        }
    }

    /**
     * Handle successful Stripe Checkout
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('pricing')->with('error', 'Invalid checkout session');
        }

        $client = $this->getAuthenticatedClient();

        if (!$client) {
            return redirect()->route('login');
        }

        try {
            $this->initStripe();

            // Retrieve the checkout session
            $session = StripeSession::retrieve([
                'id' => $sessionId,
                'expand' => ['subscription'],
            ]);

            $tier = $session->metadata->tier;
            $billing = $session->metadata->billing;
            $subscriptionId = $session->subscription->id ?? $session->subscription;

            // Update client's subscription info
            DB::table('clients')->where('id', $client->id)->update([
                'stripe_subscription_id' => $subscriptionId,
                'stripe_price_id' => $session->subscription->items->data[0]->price->id ?? null,
                'subscription_tier' => $tier,
                'billing_cycle' => $billing,
                'upload_limit' => self::UPLOAD_LIMITS[$tier] ?? 1,
                'subscription_status' => 'active',
                'billing_cycle_start' => now(),
                'show_annual_badge' => $billing === 'annual',
            ]);

            // Log the subscription payment
            $this->logSubscriptionPayment($client->id, $session);

            $tierName = ucfirst($tier);
            return redirect('/dashboard')->with('success', "Welcome to the {$tierName} Plan! Your subscription is now active.");

        } catch (\Exception $e) {
            Log::error('Stripe success callback error', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
            ]);

            return redirect()->route('pricing')->with('error', 'Unable to verify subscription. Please contact support.');
        }
    }

    /**
     * Handle canceled Stripe Checkout
     */
    public function cancel(Request $request)
    {
        return redirect()->route('pricing')->with('info', 'Subscription checkout was canceled. You can try again anytime.');
    }

    /**
     * Display subscription management page
     */
    public function manage(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            return redirect()->route('login');
        }

        // Get current month upload usage
        $uploadUsage = $this->getUploadUsageForClient($client->id);

        $data = [
            'pageTitle' => 'Manage Subscription',
            'client' => $client,
            'tier' => $client->subscription_tier ?? 'free',
            'billing' => $client->billing_cycle ?? 'monthly',
            'uploadLimit' => $client->upload_limit ?? 1,
            'uploadsUsed' => $uploadUsage,
            'uploadsRemaining' => max(0, ($client->upload_limit ?? 1) - $uploadUsage),
            'nextResetDate' => now()->startOfMonth()->addMonth()->format('F j, Y'),
            'tiers' => config('stripe.tiers'),
        ];

        return view('subscription.manage', $data);
    }

    /**
     * Upgrade subscription to a higher tier
     */
    public function upgrade(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            return redirect()->route('login');
        }

        $newTier = $request->input('tier');
        $newBilling = $request->input('billing');

        // Validate upgrade path
        if (!$this->isValidUpgrade($client->subscription_tier, $newTier)) {
            return redirect()->route('subscription.manage')->with('error', 'Invalid upgrade selection');
        }

        // If no existing Stripe subscription, redirect to checkout
        if (empty($client->stripe_subscription_id)) {
            return redirect()->route('subscribe.checkout', ['tier' => $newTier, 'billing' => $newBilling]);
        }

        try {
            $this->initStripe();

            $newPriceId = $this->getPriceId($newTier, $newBilling);

            // Update the subscription in Stripe
            $subscription = Subscription::retrieve($client->stripe_subscription_id);
            Subscription::update($client->stripe_subscription_id, [
                'items' => [[
                    'id' => $subscription->items->data[0]->id,
                    'price' => $newPriceId,
                ]],
                'proration_behavior' => 'create_prorations',
                'metadata' => [
                    'tier' => $newTier,
                    'billing' => $newBilling,
                ],
            ]);

            // Update local database
            DB::table('clients')->where('id', $client->id)->update([
                'stripe_price_id' => $newPriceId,
                'subscription_tier' => $newTier,
                'billing_cycle' => $newBilling,
                'upload_limit' => self::UPLOAD_LIMITS[$newTier] ?? 1,
                'show_annual_badge' => $newBilling === 'annual',
            ]);

            $tierName = ucfirst($newTier);
            return redirect()->route('subscription.manage')->with('success', "Upgraded to {$tierName} Plan successfully!");

        } catch (\Exception $e) {
            Log::error('Subscription upgrade error', [
                'error' => $e->getMessage(),
                'client_id' => $client->id,
                'new_tier' => $newTier,
            ]);

            return redirect()->route('subscription.manage')->with('error', 'Unable to upgrade subscription. Please try again.');
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client || empty($client->stripe_subscription_id)) {
            return redirect()->route('subscription.manage')->with('error', 'No active subscription to cancel');
        }

        try {
            $this->initStripe();

            // Cancel at period end (grace period)
            Subscription::update($client->stripe_subscription_id, [
                'cancel_at_period_end' => true,
            ]);

            // Update local database
            DB::table('clients')->where('id', $client->id)->update([
                'subscription_status' => 'canceled',
            ]);

            return redirect()->route('subscription.manage')->with('success', 'Subscription canceled. You\'ll retain access until the end of your billing period.');

        } catch (\Exception $e) {
            Log::error('Subscription cancel error', [
                'error' => $e->getMessage(),
                'client_id' => $client->id,
            ]);

            return redirect()->route('subscription.manage')->with('error', 'Unable to cancel subscription. Please try again.');
        }
    }

    /**
     * Resume a canceled subscription
     */
    public function resume(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client || empty($client->stripe_subscription_id)) {
            return redirect()->route('subscription.manage')->with('error', 'No subscription to resume');
        }

        try {
            $this->initStripe();

            // Resume the subscription
            Subscription::update($client->stripe_subscription_id, [
                'cancel_at_period_end' => false,
            ]);

            // Update local database
            DB::table('clients')->where('id', $client->id)->update([
                'subscription_status' => 'active',
            ]);

            return redirect()->route('subscription.manage')->with('success', 'Subscription resumed successfully!');

        } catch (\Exception $e) {
            Log::error('Subscription resume error', [
                'error' => $e->getMessage(),
                'client_id' => $client->id,
            ]);

            return redirect()->route('subscription.manage')->with('error', 'Unable to resume subscription. Please try again.');
        }
    }

    /**
     * Redirect to Stripe Customer Portal for billing management
     */
    public function billingPortal(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client || empty($client->stripe_customer_id)) {
            return redirect()->route('pricing');
        }

        try {
            $this->initStripe();

            $session = BillingPortalSession::create([
                'customer' => $client->stripe_customer_id,
                'return_url' => route('subscription.manage'),
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            Log::error('Billing portal error', [
                'error' => $e->getMessage(),
                'client_id' => $client->id,
            ]);

            return redirect()->route('subscription.manage')->with('error', 'Unable to access billing portal. Please try again.');
        }
    }

    /**
     * Get upload usage for current month (AJAX endpoint)
     */
    public function uploadUsage(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $uploadsUsed = $this->getUploadUsageForClient($client->id);
        $uploadLimit = $client->upload_limit ?? 1;

        return response()->json([
            'uploads_used' => $uploadsUsed,
            'upload_limit' => $uploadLimit,
            'uploads_remaining' => max(0, $uploadLimit - $uploadsUsed),
            'can_upload' => $uploadsUsed < $uploadLimit,
            'next_reset' => now()->startOfMonth()->addMonth()->format('F j, Y'),
        ]);
    }

    /**
     * Check if user can upload (API endpoint)
     */
    public function canUpload(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $uploadsUsed = $this->getUploadUsageForClient($client->id);
        $uploadLimit = $client->upload_limit ?? 1;
        $canUpload = $uploadsUsed < $uploadLimit;

        return response()->json([
            'can_upload' => $canUpload,
            'uploads_remaining' => max(0, $uploadLimit - $uploadsUsed),
            'tier' => $client->subscription_tier ?? 'free',
            'upgrade_url' => route('pricing'),
            'message' => $canUpload
                ? "You have " . ($uploadLimit - $uploadsUsed) . " upload(s) remaining this month."
                : "You've reached your monthly upload limit. Upgrade your plan for more uploads.",
        ]);
    }

    /**
     * Get subscription info (API endpoint)
     */
    public function subscriptionInfo(Request $request)
    {
        $client = $this->getAuthenticatedClient();

        if (!$client) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $uploadsUsed = $this->getUploadUsageForClient($client->id);

        return response()->json([
            'tier' => $client->subscription_tier ?? 'free',
            'billing_cycle' => $client->billing_cycle ?? 'monthly',
            'upload_limit' => $client->upload_limit ?? 1,
            'uploads_used' => $uploadsUsed,
            'uploads_remaining' => max(0, ($client->upload_limit ?? 1) - $uploadsUsed),
            'status' => $client->subscription_status ?? 'active',
            'show_annual_badge' => $client->show_annual_badge ?? false,
        ]);
    }

    // =======================================================================
    // PRIVATE HELPER METHODS
    // =======================================================================

    /**
     * Get Stripe Price ID for tier and billing cycle
     */
    private function getPriceId(string $tier, string $billing): ?string
    {
        $key = "{$tier}_{$billing}";
        return config("stripe.prices.{$key}") ?? env("STRIPE_PRICE_" . strtoupper($key));
    }

    /**
     * Get or create Stripe customer for client
     */
    private function getOrCreateStripeCustomer(object $client): string
    {
        if (!empty($client->stripe_customer_id)) {
            return $client->stripe_customer_id;
        }

        $customer = Customer::create([
            'email' => urldecode($client->email ?? ''),
            'name' => urldecode($client->name ?? ''),
            'metadata' => [
                'client_id' => $client->id,
            ],
        ]);

        DB::table('clients')->where('id', $client->id)->update([
            'stripe_customer_id' => $customer->id,
        ]);

        return $customer->id;
    }

    /**
     * Assign free plan to client (no payment required)
     */
    private function assignFreePlan(object $client)
    {
        DB::table('clients')->where('id', $client->id)->update([
            'subscription_tier' => 'free',
            'billing_cycle' => 'monthly',
            'upload_limit' => 1,
            'subscription_status' => 'active',
            'billing_cycle_start' => now(),
            'show_annual_badge' => false,
        ]);

        return redirect('/dashboard')->with('success', 'Welcome to Digiwaxx Free! You can upload 1 song per month.');
    }

    /**
     * Check if upgrade from one tier to another is valid
     */
    private function isValidUpgrade(string $currentTier, string $newTier): bool
    {
        $tierOrder = ['free' => 0, 'artist' => 1, 'label' => 2];
        return ($tierOrder[$newTier] ?? 0) > ($tierOrder[$currentTier] ?? 0);
    }

    /**
     * Get upload usage for a client in current month
     */
    private function getUploadUsageForClient(int $clientId): int
    {
        $usage = DB::table('monthly_uploads')
            ->where('client_id', $clientId)
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->value('uploads_count');

        return $usage ?? 0;
    }

    /**
     * Log subscription payment to database
     */
    private function logSubscriptionPayment(int $clientId, StripeSession $session): void
    {
        try {
            DB::table('subscription_payments')->insert([
                'client_id' => $clientId,
                'stripe_payment_intent_id' => $session->payment_intent ?? null,
                'stripe_subscription_id' => $session->subscription->id ?? $session->subscription ?? null,
                'subscription_tier' => $session->metadata->tier,
                'billing_cycle' => $session->metadata->billing,
                'amount_cents' => $session->amount_total ?? 0,
                'currency' => $session->currency ?? 'usd',
                'status' => 'succeeded',
                'paid_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log subscription payment', [
                'error' => $e->getMessage(),
                'client_id' => $clientId,
            ]);
        }
    }
}
