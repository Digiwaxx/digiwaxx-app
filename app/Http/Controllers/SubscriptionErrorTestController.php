<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Controller for testing subscription error scenarios and translation verification.
 *
 * This controller provides routes to simulate various error conditions
 * to verify that error messages are properly translated.
 *
 * NOTE: This controller should only be accessible in development/testing environments.
 */
class SubscriptionErrorTestController extends Controller
{
    /**
     * Display the error testing dashboard
     */
    public function index()
    {
        return view('subscription.error-test', [
            'pageTitle' => __('Error Testing') . ' - Digiwaxx',
        ]);
    }

    /**
     * Test: Not logged in error
     */
    public function testNotLoggedIn()
    {
        // Clear any existing session to simulate logged out state
        Session::forget('clientId');

        return redirect()->route('pricing')->with('error', __('Please log in to subscribe'));
    }

    /**
     * Test: Invalid plan selection
     */
    public function testInvalidPlan()
    {
        return redirect()->route('pricing')->with('error', __('Invalid plan selection'));
    }

    /**
     * Test: Invalid tier selection
     */
    public function testInvalidTier()
    {
        return redirect()->route('pricing')->with('error', __('Invalid plan selection'));
    }

    /**
     * Test: Stripe not configured
     */
    public function testStripeNotConfigured()
    {
        return redirect()->route('pricing')->with('error', __('Subscription plans are not configured yet. Please contact support.'));
    }

    /**
     * Test: Unable to process subscription
     */
    public function testProcessError()
    {
        return redirect()->route('pricing')->with('error', __('Unable to process subscription. Please try again.'));
    }

    /**
     * Test: Invalid checkout session
     */
    public function testInvalidSession()
    {
        return redirect()->route('pricing')->with('error', __('Invalid checkout session'));
    }

    /**
     * Test: Unable to verify subscription
     */
    public function testVerifyError()
    {
        return redirect()->route('pricing')->with('error', __('Unable to verify subscription. Please contact support.'));
    }

    /**
     * Test: Checkout canceled
     */
    public function testCheckoutCanceled()
    {
        return redirect()->route('pricing')->with('info', __('Subscription checkout was canceled. You can try again anytime.'));
    }

    /**
     * Test: Invalid upgrade selection
     */
    public function testInvalidUpgrade()
    {
        return redirect()->route('pricing')->with('error', __('Invalid upgrade selection'));
    }

    /**
     * Test: Unable to upgrade
     */
    public function testUpgradeError()
    {
        return redirect()->route('pricing')->with('error', __('Unable to upgrade subscription. Please try again.'));
    }

    /**
     * Test: No active subscription to cancel
     */
    public function testNoActiveSubscription()
    {
        return redirect()->route('pricing')->with('error', __('No active subscription to cancel'));
    }

    /**
     * Test: Subscription canceled successfully
     */
    public function testCancelSuccess()
    {
        return redirect()->route('pricing')->with('success', __("Subscription canceled. You'll retain access until the end of your billing period."));
    }

    /**
     * Test: Unable to cancel subscription
     */
    public function testCancelError()
    {
        return redirect()->route('pricing')->with('error', __('Unable to cancel subscription. Please try again.'));
    }

    /**
     * Test: No subscription to resume
     */
    public function testNoSubscriptionToResume()
    {
        return redirect()->route('pricing')->with('error', __('No subscription to resume'));
    }

    /**
     * Test: Subscription resumed successfully
     */
    public function testResumeSuccess()
    {
        return redirect()->route('pricing')->with('success', __('Subscription resumed successfully!'));
    }

    /**
     * Test: Unable to resume subscription
     */
    public function testResumeError()
    {
        return redirect()->route('pricing')->with('error', __('Unable to resume subscription. Please try again.'));
    }

    /**
     * Test: Unable to access billing portal
     */
    public function testBillingPortalError()
    {
        return redirect()->route('pricing')->with('error', __('Unable to access billing portal. Please try again.'));
    }

    /**
     * Test: Free plan success
     */
    public function testFreePlanSuccess()
    {
        return redirect()->route('pricing')->with('success', __('Welcome to Digiwaxx Free! You can upload 1 song per month.'));
    }

    /**
     * Test: Subscription activated success (dynamic tier)
     */
    public function testSubscriptionSuccess(Request $request)
    {
        $tier = $request->get('tier', 'Artist');
        return redirect()->route('pricing')->with('success', __('Welcome to the :tier Plan! Your subscription is now active.', ['tier' => ucfirst($tier)]));
    }

    /**
     * Test: Upgrade success (dynamic tier)
     */
    public function testUpgradeSuccess(Request $request)
    {
        $tier = $request->get('tier', 'Label');
        return redirect()->route('pricing')->with('success', __('Upgraded to :tier Plan successfully!', ['tier' => ucfirst($tier)]));
    }
}
