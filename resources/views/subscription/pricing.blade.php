<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Digiwaxx - Pricing Plans' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
        }

        .pricing-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 50px;
            color: #fff;
        }

        .pricing-header h1 {
            font-size: 42px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .pricing-header p {
            font-size: 18px;
            color: #a0a0a0;
            max-width: 600px;
            margin: 0 auto 30px;
        }

        /* Billing Toggle */
        .billing-toggle {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .toggle-container {
            display: flex;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 5px;
        }

        .toggle-btn {
            padding: 12px 30px;
            border: none;
            background: transparent;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .toggle-btn.active {
            background: #4CAF50;
            color: #fff;
        }

        .save-badge {
            background: #ff9800;
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }

        /* Pricing Cards */
        .pricing-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .pricing-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 30px;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .pricing-card.popular {
            border: 3px solid #4CAF50;
            transform: scale(1.05);
        }

        .pricing-card.popular:hover {
            transform: scale(1.05) translateY(-10px);
        }

        .popular-badge {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: #fff;
            padding: 8px 25px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-header {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }

        .card-header h3 {
            font-size: 28px;
            color: #1a1a2e;
            margin-bottom: 10px;
        }

        .card-header .target {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }

        .price {
            margin-top: 20px;
        }

        .price .amount {
            font-size: 56px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .price .period {
            font-size: 18px;
            color: #888;
        }

        .price .billed {
            display: block;
            font-size: 14px;
            color: #4CAF50;
            margin-top: 5px;
        }

        .annual-price {
            display: none;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .features {
            list-style: none;
            margin-bottom: 30px;
            flex: 1;
        }

        .features li {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .features li:last-child {
            border-bottom: none;
        }

        .features li::before {
            content: "\2713";
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background: #e8f5e9;
            color: #4CAF50;
            border-radius: 50%;
            font-size: 12px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .btn-subscribe {
            width: 100%;
            padding: 18px;
            font-size: 18px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-subscribe.primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: #fff;
        }

        .btn-subscribe.primary:hover {
            background: linear-gradient(135deg, #45a049, #3d8b40);
            box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
        }

        .btn-subscribe.secondary {
            background: #f5f5f5;
            color: #333;
        }

        .btn-subscribe.secondary:hover {
            background: #e0e0e0;
        }

        .btn-subscribe.label-btn {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: #fff;
        }

        .btn-subscribe.label-btn:hover {
            background: linear-gradient(135deg, #16213e, #0f172a);
            box-shadow: 0 5px 20px rgba(26, 26, 46, 0.4);
        }

        .card-note {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #888;
        }

        .current-plan-badge {
            background: #e3f2fd;
            color: #1976D2;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Annual Savings Highlight */
        .savings-highlight {
            background: #fff3e0;
            color: #e65100;
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
        }

        /* FAQ Section */
        .pricing-faq {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }

        .pricing-faq h2 {
            color: #fff;
            text-align: center;
            font-size: 32px;
            margin-bottom: 40px;
        }

        .faq-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .faq-item h4 {
            color: #fff;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .faq-item p {
            color: #a0a0a0;
            font-size: 15px;
            line-height: 1.7;
        }

        /* Alerts */
        .alert {
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .pricing-card.popular {
                transform: scale(1);
            }

            .pricing-card.popular:hover {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .pricing-header h1 {
                font-size: 32px;
            }

            .pricing-cards {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .pricing-card {
                padding: 30px 20px;
            }

            .price .amount {
                font-size: 42px;
            }

            .billing-toggle {
                flex-direction: column;
                gap: 10px;
            }

            .toggle-container {
                flex-direction: row;
            }

            .toggle-btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        /* Login Prompt */
        .login-prompt {
            text-align: center;
            color: #fff;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .login-prompt a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 600;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="pricing-page">
        <div class="pricing-header">
            <h1>Choose Your Plan</h1>
            <p>Upload your music and get real DJ validation. Start free, upgrade anytime.</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            @if(!$isLoggedIn)
                <div class="login-prompt">
                    Already have an account? <a href="/login">Log in</a> to manage your subscription.
                </div>
            @endif

            <!-- Billing Toggle -->
            <div class="billing-toggle">
                <div class="toggle-container">
                    <button type="button" class="toggle-btn active" data-billing="monthly" onclick="setBilling('monthly')">Monthly</button>
                    <button type="button" class="toggle-btn" data-billing="annual" onclick="setBilling('annual')">Annual</button>
                </div>
                <span class="save-badge">Save up to 33%</span>
            </div>
        </div>

        <div class="pricing-cards">
            <!-- FREE PLAN -->
            <div class="pricing-card free">
                <div class="card-header">
                    @if($currentTier === 'free')
                        <span class="current-plan-badge">Current Plan</span>
                    @endif
                    <h3>Free</h3>
                    <div class="target">{{ $tiers['free']['target'] }}</div>
                    <div class="price monthly-price">
                        <span class="amount">$0</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="price annual-price">
                        <span class="amount">$0</span>
                        <span class="period">/month</span>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="features">
                        @foreach($tiers['free']['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    @if($currentTier === 'free')
                        <button class="btn-subscribe secondary" disabled>Current Plan</button>
                    @else
                        <a href="{{ $isLoggedIn ? route('subscribe.checkout', ['tier' => 'free', 'billing' => 'monthly']) : '/register' }}" class="btn-subscribe secondary">Get Started Free</a>
                    @endif
                    <p class="card-note">No credit card required</p>
                </div>
            </div>

            <!-- ARTIST PLAN -->
            <div class="pricing-card artist popular">
                <div class="popular-badge">Most Popular</div>
                <div class="card-header">
                    @if($currentTier === 'artist')
                        <span class="current-plan-badge">Current Plan</span>
                    @endif
                    <h3>Artist</h3>
                    <div class="target">{{ $tiers['artist']['target'] }}</div>
                    <div class="price monthly-price">
                        <span class="amount">${{ $tiers['artist']['price_monthly'] }}</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="price annual-price">
                        <span class="amount">${{ intval($tiers['artist']['price_annual'] / 12) }}</span>
                        <span class="period">/month</span>
                        <span class="billed">Billed annually (${{ $tiers['artist']['price_annual'] }}/year)</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="savings-highlight annual-price">
                        Save ${{ $tiers['artist']['savings_annual'] }}/year with annual billing!
                    </div>
                    <ul class="features">
                        @foreach($tiers['artist']['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    @if($currentTier === 'artist')
                        <button class="btn-subscribe primary" disabled>Current Plan</button>
                    @else
                        <a href="{{ $isLoggedIn ? route('subscribe.checkout', ['tier' => 'artist', 'billing' => 'monthly']) : '/register' }}" class="btn-subscribe primary monthly-btn">
                            Subscribe Monthly
                        </a>
                        <a href="{{ $isLoggedIn ? route('subscribe.checkout', ['tier' => 'artist', 'billing' => 'annual']) : '/register' }}" class="btn-subscribe primary annual-btn" style="display: none;">
                            Subscribe Annual - Save $60/year
                        </a>
                    @endif
                </div>
            </div>

            <!-- LABEL PLAN -->
            <div class="pricing-card label">
                <div class="card-header">
                    @if($currentTier === 'label')
                        <span class="current-plan-badge">Current Plan</span>
                    @endif
                    <h3>Label</h3>
                    <div class="target">{{ $tiers['label']['target'] }}</div>
                    <div class="price monthly-price">
                        <span class="amount">${{ $tiers['label']['price_monthly'] }}</span>
                        <span class="period">/month</span>
                    </div>
                    <div class="price annual-price">
                        <span class="amount">${{ intval($tiers['label']['price_annual'] / 12) }}</span>
                        <span class="period">/month</span>
                        <span class="billed">Billed annually (${{ number_format($tiers['label']['price_annual']) }}/year)</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="savings-highlight annual-price">
                        Save ${{ $tiers['label']['savings_annual'] }}/year with annual billing!
                    </div>
                    <ul class="features">
                        @foreach($tiers['label']['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    @if($currentTier === 'label')
                        <button class="btn-subscribe label-btn" disabled>Current Plan</button>
                    @else
                        <a href="{{ $isLoggedIn ? route('subscribe.checkout', ['tier' => 'label', 'billing' => 'monthly']) : '/register' }}" class="btn-subscribe label-btn monthly-btn">
                            Subscribe Monthly
                        </a>
                        <a href="{{ $isLoggedIn ? route('subscribe.checkout', ['tier' => 'label', 'billing' => 'annual']) : '/register' }}" class="btn-subscribe label-btn annual-btn" style="display: none;">
                            Subscribe Annual - Save $600/year
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="pricing-faq">
            <h2>Frequently Asked Questions</h2>

            <div class="faq-item">
                <h4>What happens if I exceed my monthly upload limit?</h4>
                <p>You can upgrade to a higher tier anytime to increase your monthly upload limit. Uploads reset at the beginning of each calendar month.</p>
            </div>

            <div class="faq-item">
                <h4>Can I switch between monthly and annual billing?</h4>
                <p>Yes! You can upgrade to annual billing anytime to save 25-33%. When you switch, the change takes effect at your next billing cycle and you'll be credited for any unused time.</p>
            </div>

            <div class="faq-item">
                <h4>What happens when I cancel?</h4>
                <p>You'll retain access to all features until the end of your current billing period. Your uploaded tracks and their analytics remain accessible. You can resubscribe anytime.</p>
            </div>

            <div class="faq-item">
                <h4>Do unused uploads roll over to the next month?</h4>
                <p>No, upload limits reset each month. Make sure to use your monthly allocation! This ensures our DJ network can provide timely feedback on all submissions.</p>
            </div>

            <div class="faq-item">
                <h4>What payment methods do you accept?</h4>
                <p>We accept all major credit and debit cards (Visa, Mastercard, American Express, Discover) processed securely through Stripe. Your payment information is never stored on our servers.</p>
            </div>

            <div class="faq-item">
                <h4>Is there a free trial for paid plans?</h4>
                <p>The Free plan lets you test the platform with 1 upload per month. This gives you a complete feel for how Digiwaxx works before committing to a paid plan.</p>
            </div>
        </div>
    </div>

    <script>
        function setBilling(billing) {
            // Update toggle buttons
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.billing === billing) {
                    btn.classList.add('active');
                }
            });

            const isAnnual = billing === 'annual';

            // Toggle price displays
            document.querySelectorAll('.monthly-price').forEach(el => {
                el.style.display = isAnnual ? 'none' : 'block';
            });
            document.querySelectorAll('.annual-price').forEach(el => {
                el.style.display = isAnnual ? 'block' : 'none';
            });

            // Toggle subscribe buttons
            document.querySelectorAll('.monthly-btn').forEach(el => {
                el.style.display = isAnnual ? 'none' : 'inline-block';
            });
            document.querySelectorAll('.annual-btn').forEach(el => {
                el.style.display = isAnnual ? 'inline-block' : 'none';
            });

            // Update button links
            document.querySelectorAll('a.btn-subscribe').forEach(btn => {
                if (btn.href) {
                    btn.href = btn.href.replace(/\/(monthly|annual)$/, '/' + billing);
                }
            });
        }

        // Initialize with monthly billing
        document.addEventListener('DOMContentLoaded', function() {
            setBilling('monthly');
        });
    </script>
</body>
</html>
