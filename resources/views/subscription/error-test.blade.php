<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            color: #e4e4e4;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #00d4ff, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            text-align: center;
            color: #94a3b8;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        .language-info {
            text-align: center;
            margin-bottom: 30px;
            padding: 15px;
            background: rgba(124, 58, 237, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(124, 58, 237, 0.3);
        }

        .language-info strong {
            color: #7c3aed;
        }

        .section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-title {
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(124, 58, 237, 0.5);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title .icon {
            font-size: 1.5rem;
        }

        .btn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
        }

        .btn {
            display: inline-block;
            padding: 15px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
            border: none;
        }

        .btn-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-error:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(124, 58, 237, 0.3);
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: #7c3aed;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 20px;
            border: 2px solid #7c3aed;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: #7c3aed;
            color: white;
        }

        .language-switcher {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .language-switcher a {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: #e4e4e4;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .language-switcher a:hover,
        .language-switcher a.active {
            background: #7c3aed;
            color: white;
        }

        .note {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            color: #fbbf24;
        }

        .note strong {
            color: #f59e0b;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }

            .section {
                padding: 20px 15px;
            }

            .btn-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ __('Test Error Scenarios') }}</h1>
        <p class="subtitle">{{ __('Use these buttons to test various subscription error scenarios and verify translations.') }}</p>

        <div class="language-info">
            <strong>{{ __('Current Language') }}:</strong> {{ app()->getLocale() }}
            ({{ config('app.available_locales.' . app()->getLocale() . '.native', 'English') }})
        </div>

        <div class="language-switcher">
            <a href="/subscription/error-test" class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">English</a>
            <a href="/es/subscription/error-test" class="{{ app()->getLocale() == 'es' ? 'active' : '' }}">Espanol</a>
            <a href="/pt/subscription/error-test" class="{{ app()->getLocale() == 'pt' ? 'active' : '' }}">Portugues</a>
            <a href="/fr/subscription/error-test" class="{{ app()->getLocale() == 'fr' ? 'active' : '' }}">Francais</a>
            <a href="/de/subscription/error-test" class="{{ app()->getLocale() == 'de' ? 'active' : '' }}">Deutsch</a>
            <a href="/ja/subscription/error-test" class="{{ app()->getLocale() == 'ja' ? 'active' : '' }}">日本語</a>
            <a href="/ko/subscription/error-test" class="{{ app()->getLocale() == 'ko' ? 'active' : '' }}">한국어</a>
        </div>

        <div class="note">
            <strong>Note:</strong> These test routes are for development purposes only. Each button triggers a redirect to the pricing page with a translated error/success message. Check that messages appear correctly in the selected language.
        </div>

        <!-- Authentication Errors -->
        <div class="section">
            <h2 class="section-title"><span class="icon">🔐</span> {{ __('Authentication Errors') }}</h2>
            <div class="btn-grid">
                <a href="{{ url('/subscription/test/not-logged-in') }}" class="btn btn-error">{{ __('Not Logged In') }}</a>
            </div>
        </div>

        <!-- Validation Errors -->
        <div class="section">
            <h2 class="section-title"><span class="icon">⚠️</span> {{ __('Validation Errors') }}</h2>
            <div class="btn-grid">
                <a href="{{ url('/subscription/test/invalid-plan') }}" class="btn btn-error">{{ __('Invalid Plan') }}</a>
                <a href="{{ url('/subscription/test/invalid-tier') }}" class="btn btn-error">{{ __('Invalid Tier') }}</a>
                <a href="{{ url('/subscription/test/invalid-upgrade') }}" class="btn btn-error">{{ __('Invalid upgrade selection') }}</a>
            </div>
        </div>

        <!-- Configuration Errors -->
        <div class="section">
            <h2 class="section-title"><span class="icon">⚙️</span> {{ __('Configuration Errors') }}</h2>
            <div class="btn-grid">
                <a href="{{ url('/subscription/test/stripe-not-configured') }}" class="btn btn-warning">{{ __('Stripe Not Configured') }}</a>
            </div>
        </div>

        <!-- Subscription Errors -->
        <div class="section">
            <h2 class="section-title"><span class="icon">📋</span> {{ __('Subscription Errors') }}</h2>
            <div class="btn-grid">
                <a href="{{ url('/subscription/test/no-active-subscription') }}" class="btn btn-error">{{ __('No Active Subscription') }}</a>
                <a href="{{ url('/subscription/test/no-subscription-resume') }}" class="btn btn-error">{{ __('No Subscription to Resume') }}</a>
                <a href="{{ url('/subscription/test/upgrade-error') }}" class="btn btn-error">{{ __('Unable to upgrade subscription. Please try again.') }}</a>
                <a href="{{ url('/subscription/test/cancel-error') }}" class="btn btn-error">{{ __('Unable to cancel subscription. Please try again.') }}</a>
                <a href="{{ url('/subscription/test/resume-error') }}" class="btn btn-error">{{ __('Unable to resume subscription. Please try again.') }}</a>
            </div>
        </div>

        <!-- Payment Errors -->
        <div class="section">
            <h2 class="section-title"><span class="icon">💳</span> {{ __('Payment Errors') }}</h2>
            <div class="btn-grid">
                <a href="{{ url('/subscription/test/process-error') }}" class="btn btn-error">{{ __('Payment Failed') }}</a>
                <a href="{{ url('/subscription/test/invalid-session') }}" class="btn btn-error">{{ __('Invalid Session') }}</a>
                <a href="{{ url('/subscription/test/verify-error') }}" class="btn btn-error">{{ __('Unable to verify subscription. Please contact support.') }}</a>
                <a href="{{ url('/subscription/test/billing-portal-error') }}" class="btn btn-error">{{ __('Unable to access billing portal. Please try again.') }}</a>
            </div>
        </div>

        <!-- Info Messages -->
        <div class="section">
            <h2 class="section-title"><span class="icon">ℹ️</span> Info Messages</h2>
            <div class="btn-grid">
                <a href="{{ url('/subscription/test/checkout-canceled') }}" class="btn btn-info">{{ __('Subscription checkout was canceled. You can try again anytime.') }}</a>
            </div>
        </div>

        <!-- Success Messages -->
        <div class="section">
            <h2 class="section-title"><span class="icon">✅</span> {{ __('Success Messages') }}</h2>
            <div class="btn-grid">
                <a href="{{ url('/subscription/test/free-plan-success') }}" class="btn btn-success">{{ __('Free Plan Success') }}</a>
                <a href="{{ url('/subscription/test/subscription-success?tier=artist') }}" class="btn btn-success">Artist Plan Success</a>
                <a href="{{ url('/subscription/test/subscription-success?tier=label') }}" class="btn btn-success">Label Plan Success</a>
                <a href="{{ url('/subscription/test/upgrade-success?tier=artist') }}" class="btn btn-success">{{ __('Upgrade Success') }} (Artist)</a>
                <a href="{{ url('/subscription/test/upgrade-success?tier=label') }}" class="btn btn-success">{{ __('Upgrade Success') }} (Label)</a>
                <a href="{{ url('/subscription/test/cancel-success') }}" class="btn btn-success">{{ __('Cancel Success') }}</a>
                <a href="{{ url('/subscription/test/resume-success') }}" class="btn btn-success">{{ __('Resume Success') }}</a>
            </div>
        </div>

        <div class="nav-links">
            <a href="{{ route('pricing') }}">{{ __('Back to Pricing') }}</a>
            <a href="/">{{ __('Home') }}</a>
        </div>
    </div>
</body>
</html>
