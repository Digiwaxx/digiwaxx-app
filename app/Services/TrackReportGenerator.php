<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TrackReportGenerator
{
    /**
     * Generate track analytics report data
     *
     * @param int $trackId
     * @return array
     */
    public static function generateReportData($trackId)
    {
        // Get track details
        $track = DB::table('tracks')->where('id', $trackId)->first();

        if (!$track) {
            return null;
        }

        // Get all reviews for this track
        $reviews = DB::table('tracks_reviews as tr')
            ->leftJoin('members as m', 'tr.member', '=', 'm.id')
            ->where('tr.track', $trackId)
            ->select(
                'tr.whatrate',
                'tr.additionalcomments',
                'tr.added',
                'tr.countryName',
                'm.fname',
                'm.lname',
                'm.uname'
            )
            ->orderBy('tr.added', 'desc')
            ->get();

        // Calculate review statistics
        $totalReviews = count($reviews);
        $averageRating = 0;
        $ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

        if ($totalReviews > 0) {
            $sumRatings = 0;
            foreach ($reviews as $review) {
                $rating = (int) $review->whatrate;
                $sumRatings += $rating;
                if (isset($ratingDistribution[$rating])) {
                    $ratingDistribution[$rating]++;
                }
            }
            $averageRating = round($sumRatings / $totalReviews, 2);
        }

        // Get download statistics
        $totalDownloads = DB::table('track_member_downloads')
            ->where('trackId', $trackId)
            ->count();

        $downloadsThisWeek = DB::table('track_member_downloads')
            ->where('trackId', $trackId)
            ->where('downloadedDateTime', '>=', date('Y-m-d', strtotime('monday this week')))
            ->count();

        $downloadsThisMonth = DB::table('track_member_downloads')
            ->where('trackId', $trackId)
            ->where('downloadedDateTime', '>=', date('Y-m-01'))
            ->count();

        // Get play statistics
        $totalPlays = DB::table('play_tracks')
            ->where('track_id', $trackId)
            ->sum('play_count');

        // Get geographic data from reviews
        $topCountries = DB::table('tracks_reviews')
            ->select('countryName', DB::raw('COUNT(*) as count'))
            ->where('track', $trackId)
            ->where('countryName', '!=', '')
            ->groupBy('countryName')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return [
            'track' => $track,
            'reviews' => $reviews,
            'statistics' => [
                'total_reviews' => $totalReviews,
                'average_rating' => $averageRating,
                'rating_distribution' => $ratingDistribution,
                'total_downloads' => $totalDownloads,
                'downloads_this_week' => $downloadsThisWeek,
                'downloads_this_month' => $downloadsThisMonth,
                'total_plays' => $totalPlays,
            ],
            'top_countries' => $topCountries,
            'generated_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Generate HTML report
     *
     * @param int $trackId
     * @return string
     */
    public static function generateHTMLReport($trackId)
    {
        $data = self::generateReportData($trackId);

        if (!$data) {
            return '<html><body><h1>Track not found</h1></body></html>';
        }

        $track = $data['track'];
        $stats = $data['statistics'];
        $reviews = $data['reviews'];
        $topCountries = $data['top_countries'];

        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Track Analytics Report - ' . htmlspecialchars($track->title) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        .track-info {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-box {
            background-color: #fff;
            border: 2px solid #e0e0e0;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #4CAF50;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .rating-bar {
            background-color: #e0e0e0;
            height: 20px;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
        }
        .rating-fill {
            background-color: #4CAF50;
            height: 100%;
        }
        .review-item {
            background-color: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #4CAF50;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .stars {
            color: #FFD700;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Track Analytics Report</h1>
        <p>Generated on ' . date('F j, Y \a\t g:i A') . '</p>
    </div>

    <div class="track-info">
        <h2>' . htmlspecialchars($track->title) . '</h2>
        <p><strong>Artist:</strong> ' . htmlspecialchars($track->artist) . '</p>
        <p><strong>Album:</strong> ' . htmlspecialchars($track->album) . '</p>
        <p><strong>Label:</strong> ' . htmlspecialchars($track->label) . '</p>
    </div>

    <h2>Performance Statistics</h2>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">' . $stats['total_reviews'] . '</div>
            <div class="stat-label">Total Reviews</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">' . $stats['average_rating'] . '</div>
            <div class="stat-label">Average Rating</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">' . $stats['total_downloads'] . '</div>
            <div class="stat-label">Total Downloads</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">' . $stats['total_plays'] . '</div>
            <div class="stat-label">Total Plays</div>
        </div>
    </div>

    <h3>Rating Distribution</h3>';

        // Rating distribution bars
        foreach ([5, 4, 3, 2, 1] as $rating) {
            $count = $stats['rating_distribution'][$rating];
            $percentage = $stats['total_reviews'] > 0 ? ($count / $stats['total_reviews']) * 100 : 0;
            $html .= '
    <div style="margin: 10px 0;">
        <strong>' . $rating . ' Stars:</strong> ' . $count . ' reviews (' . round($percentage, 1) . '%)
        <div class="rating-bar">
            <div class="rating-fill" style="width: ' . $percentage . '%"></div>
        </div>
    </div>';
        }

        $html .= '
    <h3>Recent Downloads</h3>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number">' . $stats['downloads_this_week'] . '</div>
            <div class="stat-label">This Week</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">' . $stats['downloads_this_month'] . '</div>
            <div class="stat-label">This Month</div>
        </div>
    </div>';

        // Top countries
        if (count($topCountries) > 0) {
            $html .= '
    <h3>Top Countries (by Reviews)</h3>
    <table>
        <tr>
            <th>Country</th>
            <th>Reviews</th>
        </tr>';
            foreach ($topCountries as $country) {
                $html .= '
        <tr>
            <td>' . htmlspecialchars($country->countryName) . '</td>
            <td>' . $country->count . '</td>
        </tr>';
            }
            $html .= '
    </table>';
        }

        // Reviews section
        $html .= '
    <h2>DJ Reviews (' . $stats['total_reviews'] . ' total)</h2>';

        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                $djName = !empty($review->fname) && !empty($review->lname)
                    ? htmlspecialchars($review->fname . ' ' . $review->lname)
                    : (!empty($review->uname) ? htmlspecialchars($review->uname) : 'Anonymous DJ');

                $stars = str_repeat('⭐', (int) $review->whatrate);
                $comment = !empty($review->additionalcomments) ? htmlspecialchars($review->additionalcomments) : '<em>No comment provided</em>';

                $html .= '
    <div class="review-item">
        <div class="review-header">
            <span>' . $djName . '</span>
            <span class="stars">' . $stars . '</span>
        </div>
        <p>' . $comment . '</p>
        <small style="color: #666;">
            ' . date('F j, Y', strtotime($review->added)) . '
            ' . (!empty($review->countryName) ? ' • ' . htmlspecialchars($review->countryName) : '') . '
        </small>
    </div>';
            }
        } else {
            $html .= '<p>No reviews yet.</p>';
        }

        $html .= '
    <div class="footer">
        <p><strong>Digiwaxx</strong> - Track Analytics Report</p>
        <p>This report contains confidential information. Do not share without permission.</p>
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Get download URL for track report
     *
     * @param int $trackId
     * @return string
     */
    public static function getReportDownloadUrl($trackId)
    {
        // Generate a secure token for the report
        $token = base64_encode($trackId . '|' . time());

        return url('/track/report/download/' . $token);
    }
}
