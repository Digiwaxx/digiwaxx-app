<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Manage Subscription - Digiwaxx' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        h1 {
            color: #fff;
            font-size: 32px;
            margin-bottom: 30px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .alert-info { background: #d1ecf1; color: #0c5460; }
        .card {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .card-header h2 { font-size: 22px; color: #1a1a2e; }
        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .badge-free { background: #e0e0e0; color: #555; }
        .badge-artist { background: #4CAF50; color: #fff; }
        .badge-label { background: #1a1a2e; color: #fff; }
        .badge-annual { background: #ff9800; color: #fff; margin-left: 10px; }
        .badge-canceled { background: #f44336; color: #fff; margin-left: 10px; }
        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .stat-row:last-child { border-bottom: none; }
        .stat-label { color: #666; font-size: 15px; }
        .stat-value { font-weight: 600; color: #1a1a2e; font-size: 15px; }
        .progress-container { margin: 25px 0; }
        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .progress-bar {
            height: 12px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50, #45a049);
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        .progress-fill.warning { background: linear-gradient(90deg, #ff9800, #f57c00); }
        .progress-fill.danger { background: linear-gradient(90deg, #f44336, #d32f2f); }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: #fff;
        }
        .btn-primary:hover { box-shadow: 0 5px 20px rgba(76,175,80,0.4); }
        .btn-secondary { background: #f5f5f5; color: #333; }
        .btn-secondary:hover { background: #e0e0e0; }
        .btn-danger { background: #f44336; color: #fff; }
        .btn-danger:hover { background: #d32f2f; }
        .btn-group { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 20px; }
        .upgrade-section {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
        }
        .upgrade-section h3 { color: #2e7d32; margin-bottom: 10px; }
        .upgrade-section p { color: #555; margin-bottom: 15px; }
        .features-list { list-style: none; margin: 15px 0; }
        .features-list li {
            padding: 8px 0;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .features-list li::before {
            content: "\2713";
            color: #4CAF50;
            font-weight: bold;
        }
        @media (max-width: 600px) {
            .card-header { flex-direction: column; align-items: flex-start; gap: 10px; }
            .btn-group { flex-direction: column; }
            .btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Subscription</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Current Plan Card -->
        <div class="card">
            <div class="card-header">
                <h2>Current Plan</h2>
                <div>
                    <span class="badge badge-{{ $tier }}">{{ ucfirst($tier) }} Plan</span>
                    @if($billing === 'annual')
                        <span class="badge badge-annual">Annual Member</span>
                    @endif
                    @if(($client->subscription_status ?? 'active') === 'canceled')
                        <span class="badge badge-canceled">Canceled</span>
                    @endif
                </div>
            </div>

            <div class="stat-row">
                <span class="stat-label">Billing Cycle</span>
                <span class="stat-value">{{ ucfirst($billing) }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Monthly Upload Limit</span>
                <span class="stat-value">{{ $uploadLimit }} song(s)</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Status</span>
                <span class="stat-value">{{ ucfirst($client->subscription_status ?? 'Active') }}</span>
            </div>

            @if($tier !== 'free')
            <div class="btn-group">
                <a href="{{ route('billing.portal') }}" class="btn btn-secondary">Manage Billing</a>
                @if(($client->subscription_status ?? 'active') === 'canceled')
                    <form action="{{ route('subscription.resume') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Resume Subscription</button>
                    </form>
                @else
                    <form action="{{ route('subscription.cancel') }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel? You\'ll retain access until the end of your billing period.');">
                        @csrf
                        <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                    </form>
                @endif
            </div>
            @endif
        </div>

        <!-- Upload Usage Card -->
        <div class="card">
            <div class="card-header">
                <h2>Monthly Uploads</h2>
            </div>

            <div class="progress-container">
                <div class="progress-header">
                    <span>{{ $uploadsUsed }} of {{ $uploadLimit }} uploads used</span>
                    <span>{{ $uploadsRemaining }} remaining</span>
                </div>
                @php
                    $percentage = $uploadLimit > 0 ? ($uploadsUsed / $uploadLimit) * 100 : 100;
                    $progressClass = $percentage >= 100 ? 'danger' : ($percentage >= 75 ? 'warning' : '');
                @endphp
                <div class="progress-bar">
                    <div class="progress-fill {{ $progressClass }}" style="width: {{ min($percentage, 100) }}%"></div>
                </div>
            </div>

            <div class="stat-row">
                <span class="stat-label">Resets On</span>
                <span class="stat-value">{{ $nextResetDate }}</span>
            </div>

            @if($uploadsUsed >= $uploadLimit && $tier !== 'label')
            <div class="upgrade-section">
                <h3>Need more uploads?</h3>
                <p>Upgrade your plan to increase your monthly upload limit.</p>
                <a href="{{ route('pricing') }}" class="btn btn-primary">View Plans</a>
            </div>
            @endif
        </div>

        <!-- Upgrade Options -->
        @if($tier !== 'label')
        <div class="card">
            <div class="card-header">
                <h2>Upgrade Your Plan</h2>
            </div>

            @if($tier === 'free')
            <div class="upgrade-section" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb);">
                <h3>Artist Plan - $20/month</h3>
                <p>Perfect for independent artists and producers</p>
                <ul class="features-list">
                    <li>2 song uploads per month</li>
                    <li>Full validation reports</li>
                    <li>DJ demand reports</li>
                    <li>Priority support</li>
                </ul>
                <a href="{{ route('subscribe.checkout', ['tier' => 'artist', 'billing' => 'monthly']) }}" class="btn btn-primary">Upgrade to Artist</a>
            </div>
            @endif

            <div class="upgrade-section" style="background: linear-gradient(135deg, #fce4ec, #f8bbd9); margin-top: 20px;">
                <h3>Label Plan - $149/month</h3>
                <p>For record labels and management companies</p>
                <ul class="features-list">
                    <li>20 song uploads per month</li>
                    <li>Campaign creation tools</li>
                    <li>Multi-user team access</li>
                    <li>Dedicated account manager</li>
                </ul>
                <a href="{{ route('subscribe.checkout', ['tier' => 'label', 'billing' => 'monthly']) }}" class="btn btn-primary">Upgrade to Label</a>
            </div>
        </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="/dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
