<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReviewNotificationsController extends Controller
{
    /**
     * Handle unsubscribe request from email link
     *
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe($token)
    {
        // Validate token format
        if (strlen($token) != 64) {
            return view('emails.unsubscribe_error', [
                'message' => 'Invalid unsubscribe link. Please contact support.'
            ]);
        }

        // Find client by unsubscribe token
        $client = DB::table('clients')
            ->where('review_notification_token', $token)
            ->first();

        if (!$client) {
            return view('emails.unsubscribe_error', [
                'message' => 'Invalid or expired unsubscribe link. Please contact support.'
            ]);
        }

        // Check if already unsubscribed
        if ($client->trackReviewEmailsActivated == 0) {
            return view('emails.unsubscribe_already', [
                'clientName' => $client->name
            ]);
        }

        return view('emails.unsubscribe_confirm', [
            'clientName' => $client->name,
            'token' => $token
        ]);
    }

    /**
     * Process unsubscribe confirmation
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function confirmUnsubscribe(Request $request, $token)
    {
        // Validate token
        $client = DB::table('clients')
            ->where('review_notification_token', $token)
            ->first();

        if (!$client) {
            return view('emails.unsubscribe_error', [
                'message' => 'Invalid unsubscribe link.'
            ]);
        }

        // Update client to disable review emails
        DB::table('clients')
            ->where('id', $client->id)
            ->update([
                'trackReviewEmailsActivated' => 0,
                'updated_at' => now()
            ]);

        return view('emails.unsubscribe_success', [
            'clientName' => $client->name,
            'resubscribeToken' => $token
        ]);
    }

    /**
     * Resubscribe to review notifications
     *
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function resubscribe($token)
    {
        // Validate token
        $client = DB::table('clients')
            ->where('review_notification_token', $token)
            ->first();

        if (!$client) {
            return view('emails.unsubscribe_error', [
                'message' => 'Invalid resubscribe link.'
            ]);
        }

        // Check if already subscribed
        if ($client->trackReviewEmailsActivated == 1) {
            return view('emails.resubscribe_already', [
                'clientName' => $client->name
            ]);
        }

        // Reactivate review emails
        DB::table('clients')
            ->where('id', $client->id)
            ->update([
                'trackReviewEmailsActivated' => 1,
                'updated_at' => now()
            ]);

        return view('emails.resubscribe_success', [
            'clientName' => $client->name
        ]);
    }

    /**
     * Generate or get unsubscribe token for a client
     *
     * @param int $clientId
     * @return string
     */
    public static function getUnsubscribeToken($clientId)
    {
        $client = DB::table('clients')->where('id', $clientId)->first();

        if (!$client) {
            return null;
        }

        // If client already has a token, return it
        if (!empty($client->review_notification_token)) {
            return $client->review_notification_token;
        }

        // Generate new token
        $token = bin2hex(random_bytes(32)); // 64 character hex string

        // Save token to database
        DB::table('clients')
            ->where('id', $clientId)
            ->update([
                'review_notification_token' => $token,
                'updated_at' => now()
            ]);

        return $token;
    }
}
