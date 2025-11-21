<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Review on {{ $trackTitle }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .review-box {
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .rating {
            font-size: 28px;
            margin: 10px 0;
            color: #FFD700;
        }
        .track-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .track-info strong {
            color: #1976D2;
        }
        .comment {
            font-style: italic;
            color: #555;
            padding: 15px;
            background-color: #fff;
            border-radius: 4px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px 5px;
            background-color: #4CAF50;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn-secondary {
            background-color: #2196F3;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .unsubscribe {
            margin-top: 20px;
            font-size: 11px;
            color: #999;
        }
        .unsubscribe a {
            color: #666;
            text-decoration: underline;
        }
        .meta-info {
            color: #666;
            font-size: 14px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎵 New Review Received!</h1>
            <p style="margin: 0; color: #666;">You've got feedback from a DJ</p>
        </div>

        <p>Hi {{ $clientName }},</p>

        <p>Great news! DJ <strong>{{ $djName }}</strong> just reviewed your track:</p>

        <div class="track-info">
            <strong>Track:</strong> {{ $trackTitle }}<br>
            <strong>Artist:</strong> {{ $trackArtist }}
        </div>

        <div class="review-box">
            <div class="meta-info">
                <strong>Reviewed by:</strong> {{ $djName }}<br>
                <strong>Date:</strong> {{ $reviewDate }}
            </div>

            <div class="rating">
                {{ $stars }} ({{ $rating }} out of 5 stars)
            </div>

            @if(!empty($comment))
            <div class="comment">
                <strong>Review Comment:</strong><br>
                "{{ $comment }}"
            </div>
            @else
            <p style="color: #999; font-style: italic;">No written comment provided.</p>
            @endif
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $trackUrl }}" class="btn">View Full Track Analytics</a>
            <a href="{{ $reportDownloadUrl }}" class="btn btn-secondary">Download Full Report (PDF)</a>
        </div>

        <div class="footer">
            <p><strong>Digiwaxx</strong><br>
            Your Music Distribution Platform</p>

            <div class="unsubscribe">
                <p>Don't want to receive review notifications?<br>
                <a href="{{ $unsubscribeUrl }}">Click here to unsubscribe</a></p>
            </div>
        </div>
    </div>
</body>
</html>
