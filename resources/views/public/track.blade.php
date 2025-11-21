<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO & Social Media Meta Tags -->
    <title>{{ $track->title }} by {{ $track->artist }} | Digiwaxx</title>
    <meta name="description" content="Listen to {{ $track->title }} by {{ $track->artist }} on Digiwaxx - supported by {{ $reviewStats->total_reviews }} DJs">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="music.song">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $track->title }} by {{ $track->artist }}">
    <meta property="og:description" content="🎧 Supported by {{ $reviewStats->total_reviews }} DJs on Digiwaxx! Listen now.">
    <meta property="og:image" content="{{ $track->imgpage ?? $track->img ?? asset('images/default-artwork.jpg') }}">
    <meta property="og:site_name" content="Digiwaxx">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $track->title }} by {{ $track->artist }}">
    <meta name="twitter:description" content="🎧 Supported by {{ $reviewStats->total_reviews }} DJs on Digiwaxx!">
    <meta name="twitter:image" content="{{ $track->imgpage ?? $track->img }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .artwork {
            width: 100%;
            aspect-ratio: 1;
            background: #000;
            position: relative;
        }

        .artwork img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content {
            padding: 30px;
        }

        .track-info {
            margin-bottom: 30px;
        }

        h1 {
            font-size: 32px;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .artist {
            font-size: 24px;
            color: #666;
            margin-bottom: 20px;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 14px;
        }

        .meta-item i {
            color: #667eea;
        }

        .stats {
            display: flex;
            gap: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .audio-player {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .audio-player audio {
            width: 100%;
        }

        .cta {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
        }

        .cta h3 {
            color: white;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: #667eea;
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
            h1 { font-size: 24px; }
            .artist { font-size: 18px; }
            .stats { flex-direction: column; gap: 15px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Artwork -->
        <div class="artwork">
            <img src="{{ $track->imgpage ?? $track->img ?? asset('images/default-artwork.jpg') }}" alt="{{ $track->title }} artwork">
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Track Info -->
            <div class="track-info">
                <h1>{{ $track->title }}</h1>
                <div class="artist">{{ $track->artist }}</div>

                <div class="meta">
                    @if($track->label)
                    <div class="meta-item">
                        <i class="fas fa-record-vinyl"></i>
                        <span>{{ $track->label }}</span>
                    </div>
                    @endif

                    @if($track->bpm)
                    <div class="meta-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>{{ $track->bpm }} BPM</span>
                    </div>
                    @endif

                    @if(isset($track->genre))
                    <div class="meta-item">
                        <i class="fas fa-music"></i>
                        <span>{{ $track->genre }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="stats">
                <div class="stat">
                    <div class="stat-value">{{ $reviewStats->total_reviews }}</div>
                    <div class="stat-label">DJ Reviews</div>
                </div>

                <div class="stat">
                    <div class="stat-value">{{ number_format($reviewStats->avg_rating, 1) }} <i class="fas fa-star" style="color: #ffc107; font-size: 18px;"></i></div>
                    <div class="stat-label">Avg Rating</div>
                </div>

                <div class="stat">
                    <div class="stat-value">{{ $reviewStats->will_play_count }}</div>
                    <div class="stat-label">DJs Playing</div>
                </div>
            </div>

            <!-- Audio Preview -->
            @if($mp3s && $mp3s->count() > 0)
            <div class="audio-player">
                <h3 style="margin-bottom: 15px; color: #1a1a1a;">
                    <i class="fas fa-headphones"></i> Preview
                </h3>
                @foreach($mp3s as $mp3)
                    @if($mp3->preview == 1)
                    <audio controls preload="metadata">
                        <source src="{{ url('/member/pcloud/stream/' . $mp3->id) }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    @break
                    @endif
                @endforeach
            </div>
            @endif

            <!-- CTA -->
            <div class="cta">
                <h3>Get Full Access to Thousands of Tracks</h3>
                <a href="{{ url('/') }}" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Join Digiwaxx
                </a>
            </div>
        </div>

        <!-- Logo -->
        <div class="logo">
            <strong>DIGIWAXX</strong> - Professional DJ Pool
        </div>
    </div>

    <script>
        // Track audio play event
        document.querySelector('audio')?.addEventListener('play', function() {
            console.log('Audio played');
        });
    </script>
</body>
</html>
