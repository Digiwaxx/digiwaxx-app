<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $djName = $member->display_name ?? ($member->fname ?? '') . ' ' . ($member->lname ?? '') ?? 'Anonymous DJ';
        $djName = trim($djName) ?: 'Anonymous DJ';
        $comment = urldecode($review->additionalcomments ?? '');
        $stars = str_repeat('⭐', $review->whatrate);
    @endphp

    <!-- SEO & Social Media Meta Tags -->
    <title>{{ $stars }} DJ {{ $djName }} Review | {{ $track->title }}</title>
    <meta name="description" content="{{ $comment }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="music.song">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="DJ {{ $djName }} rated '{{ $track->title }}' {{ $review->whatrate }} stars!">
    <meta property="og:description" content="{{ Str::limit($comment, 200) }}">
    <meta property="og:image" content="{{ $track->imgpage ?? $track->img }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $stars }} from DJ {{ $djName }}">
    <meta name="twitter:description" content="{{ Str::limit($comment, 200) }}">
    <meta name="twitter:image" content="{{ $track->imgpage ?? $track->img }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .stars {
            font-size: 48px;
            margin-bottom: 20px;
            line-height: 1;
        }

        .review-badge {
            background: rgba(255,255,255,0.2);
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .quote-section {
            padding: 40px 30px;
        }

        .quote-mark {
            font-size: 60px;
            color: #f093fb;
            line-height: 0;
            height: 30px;
            margin-bottom: 20px;
        }

        .review-text {
            font-size: 20px;
            line-height: 1.6;
            color: #1a1a1a;
            font-style: italic;
            margin-bottom: 30px;
        }

        .dj-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .dj-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .dj-details {
            flex: 1;
        }

        .dj-name {
            font-weight: bold;
            font-size: 18px;
            color: #1a1a1a;
        }

        .dj-location {
            font-size: 14px;
            color: #666;
        }

        .track-info {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .track-artwork {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            margin: 0 auto 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .track-artwork img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .track-title {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .track-artist {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .logo {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .stars { font-size: 36px; }
            .review-text { font-size: 18px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Stars -->
        <div class="header">
            <div class="stars">{{ $stars }}</div>
            <div class="review-badge">DJ Review</div>
        </div>

        <!-- Quote Section -->
        <div class="quote-section">
            <div class="quote-mark">"</div>
            <div class="review-text">{{ $comment }}</div>

            <!-- DJ Info -->
            <div class="dj-info">
                <div class="dj-avatar">
                    {{ strtoupper(substr($djName, 0, 1)) }}
                </div>
                <div class="dj-details">
                    <div class="dj-name">{{ $djName }}</div>
                    @if(isset($member->city) && $member->city)
                    <div class="dj-location">
                        <i class="fas fa-map-marker-alt"></i> {{ $member->city }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Track Info -->
            <div class="track-info">
                <div class="track-artwork">
                    <img src="{{ $track->imgpage ?? $track->img }}" alt="{{ $track->title }}">
                </div>
                <div class="track-title">{{ $track->title }}</div>
                <div class="track-artist">{{ $track->artist }}</div>
                <a href="{{ url('/') }}" class="btn">
                    <i class="fas fa-headphones"></i> Listen on Digiwaxx
                </a>
            </div>
        </div>

        <!-- Logo -->
        <div class="logo">
            <strong>DIGIWAXX</strong> - Professional DJ Pool
        </div>
    </div>
</body>
</html>
