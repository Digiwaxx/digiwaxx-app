<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe API Keys
    |--------------------------------------------------------------------------
    |
    | Your Stripe API credentials for processing payments.
    | Get these from: https://dashboard.stripe.com/apikeys
    |
    */

    'secret' => env('STRIPE_SECRET'),
    'publishable' => env('STRIPE_PUBLISHABLE'),
    'currency' => env('STRIPE_CURRENCY', 'usd'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Subscription Price IDs
    |--------------------------------------------------------------------------
    |
    | Stripe Price IDs for each subscription tier and billing cycle.
    | Create these products/prices in Stripe Dashboard first:
    | https://dashboard.stripe.com/products
    |
    */

    'prices' => [
        'artist_monthly' => env('STRIPE_PRICE_ARTIST_MONTHLY'),
        'artist_annual' => env('STRIPE_PRICE_ARTIST_ANNUAL'),
        'label_monthly' => env('STRIPE_PRICE_LABEL_MONTHLY'),
        'label_annual' => env('STRIPE_PRICE_LABEL_ANNUAL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Subscription Tiers Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for each subscription tier including upload limits,
    | features, and pricing display information.
    |
    */

    'tiers' => [
        'free' => [
            'name' => 'Free',
            'upload_limit' => 1,
            'price_monthly' => 0,
            'price_annual' => 0,
            'features' => [
                '1 song upload per month',
                'Basic validation reports',
                'DJ ratings & comments',
                'Limited analytics',
                'Community support',
            ],
            'target' => 'Artists testing the platform',
        ],
        'artist' => [
            'name' => 'Artist',
            'upload_limit' => 2,
            'price_monthly' => 20,
            'price_annual' => 180, // $15/month billed annually
            'savings_annual' => 60, // $60/year savings
            'discount_percent' => 25,
            'features' => [
                '2 song uploads per month',
                'Full validation reports',
                'DJ demand reports',
                'Regional reaction reports',
                'Advanced analytics dashboard',
                'Email notifications',
                'Priority support',
                'Ad-free experience',
            ],
            'target' => 'Independent artists, producers',
            'badge' => 'Annual Member',
        ],
        'label' => [
            'name' => 'Label',
            'upload_limit' => 20,
            'price_monthly' => 149,
            'price_annual' => 1188, // $99/month billed annually
            'savings_annual' => 600, // $600/year savings
            'discount_percent' => 33,
            'features' => [
                '20 song uploads per month',
                'Everything in Artist Plan',
                'Campaign creation tools',
                'Label portfolio analytics',
                'Artist roster management',
                'Multi-user team access',
                'White-label reports',
                'API access',
                'Dedicated account manager',
                'Priority upload queue',
            ],
            'target' => 'Record labels, management companies',
            'badge' => 'Premium Label',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Limits by Tier
    |--------------------------------------------------------------------------
    |
    | Quick lookup for upload limits by tier name.
    |
    */

    'upload_limits' => [
        'free' => 1,
        'artist' => 2,
        'label' => 20,
    ],
];
