<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe from Review Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #FF9800;
            margin-bottom: 20px;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 20px 10px;
            background-color: #F44336;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-cancel {
            background-color: #9E9E9E;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .warning-box {
            background-color: #FFF3E0;
            border-left: 4px solid #FF9800;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        form {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⚠️</div>
        <h1>Unsubscribe from Review Notifications?</h1>

        <p>Hi <strong>{{ $clientName }}</strong>,</p>

        <div class="warning-box">
            <strong>Are you sure?</strong><br>
            If you unsubscribe, you will NO LONGER receive email notifications when DJs review your tracks.

            <p style="margin-top: 15px;"><strong>You will miss out on:</strong></p>
            <ul style="text-align: left;">
                <li>Instant notifications when DJs review your tracks</li>
                <li>Valuable feedback from professional DJs</li>
                <li>Track performance insights</li>
            </ul>
        </div>

        <p><strong>Note:</strong> You can always resubscribe later from your account settings or by clicking the resubscribe link in any previous email.</p>

        <form action="{{ url('/unsubscribe-reviews/confirm/' . $token) }}" method="POST">
            @csrf
            <button type="submit" class="btn">Yes, Unsubscribe Me</button>
            <a href="{{ url('/') }}" class="btn btn-cancel">Cancel (Keep Subscribed)</a>
        </form>
    </div>
</body>
</html>
