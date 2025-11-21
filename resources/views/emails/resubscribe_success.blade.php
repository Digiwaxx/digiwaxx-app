<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successfully Resubscribed</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🎉</div>
        <h1>Welcome Back!</h1>

        <p>Hi <strong>{{ $clientName }}</strong>,</p>

        <div class="info-box">
            <p><strong>You've been resubscribed to review notification emails!</strong></p>

            <p style="margin-top: 15px;">From now on, you'll receive instant email notifications when:</p>
            <ul>
                <li>DJs leave reviews on your tracks</li>
                <li>New ratings are posted</li>
                <li>Feedback comments are added</li>
            </ul>
        </div>

        <p>Each email will include a link to download full analytics reports for your tracks.</p>

        <a href="{{ url('/') }}" class="btn">Return to Homepage</a>
    </div>
</body>
</html>
