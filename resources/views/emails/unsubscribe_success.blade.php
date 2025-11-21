<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successfully Unsubscribed</title>
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
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #E8F5E9;
            border-left: 4px solid #4CAF50;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 20px 10px;
            background-color: #2196F3;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn:hover {
            opacity: 0.9;
        }
        a {
            color: #2196F3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">✅</div>
        <h1>You've Been Unsubscribed</h1>

        <p>Hi <strong>{{ $clientName }}</strong>,</p>

        <div class="info-box">
            <p><strong>You will no longer receive email notifications when DJs review your tracks.</strong></p>

            <p style="margin-top: 15px;">However, you can still:</p>
            <ul>
                <li>View all reviews by logging into your Digiwaxx account</li>
                <li>Download track analytics reports anytime</li>
                <li>See review summaries in your dashboard</li>
            </ul>
        </div>

        <p><strong>Changed your mind?</strong></p>
        <p>You can resubscribe anytime by clicking the button below:</p>

        <a href="{{ url('/resubscribe-reviews/' . $resubscribeToken) }}" class="btn">Resubscribe to Review Notifications</a>

        <p style="margin-top: 30px; font-size: 14px; color: #666;">
            <a href="{{ url('/') }}">Return to Homepage</a>
        </p>
    </div>
</body>
</html>
