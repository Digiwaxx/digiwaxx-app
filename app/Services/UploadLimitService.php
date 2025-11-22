<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

/**
 * Service class for managing upload limits based on subscription tiers.
 *
 * Subscription Tiers:
 * - free: 1 upload/month
 * - artist: 2 uploads/month
 * - label: 20 uploads/month
 */
class UploadLimitService
{
    /**
     * Upload limits by subscription tier
     */
    private const UPLOAD_LIMITS = [
        'free' => 1,
        'artist' => 2,
        'label' => 20,
    ];

    /**
     * Check if a client can upload based on their subscription tier
     *
     * @param int $clientId
     * @return array{can_upload: bool, uploads_used: int, upload_limit: int, uploads_remaining: int, message: string}
     */
    public function canUpload(int $clientId): array
    {
        $client = $this->getClient($clientId);

        if (!$client) {
            return [
                'can_upload' => false,
                'uploads_used' => 0,
                'upload_limit' => 0,
                'uploads_remaining' => 0,
                'message' => 'Client not found',
            ];
        }

        $uploadLimit = $client->upload_limit ?? self::UPLOAD_LIMITS[$client->subscription_tier ?? 'free'] ?? 1;
        $uploadsUsed = $this->getUploadsUsedThisMonth($clientId);
        $uploadsRemaining = max(0, $uploadLimit - $uploadsUsed);
        $canUpload = $uploadsUsed < $uploadLimit;

        return [
            'can_upload' => $canUpload,
            'uploads_used' => $uploadsUsed,
            'upload_limit' => $uploadLimit,
            'uploads_remaining' => $uploadsRemaining,
            'tier' => $client->subscription_tier ?? 'free',
            'message' => $canUpload
                ? "You have {$uploadsRemaining} upload(s) remaining this month."
                : "You've reached your monthly upload limit of {$uploadLimit} song(s). Upgrade your plan for more uploads.",
        ];
    }

    /**
     * Check if the currently logged-in client can upload
     *
     * @return array
     */
    public function canCurrentClientUpload(): array
    {
        $clientId = Session::get('clientId');

        if (!$clientId) {
            return [
                'can_upload' => false,
                'uploads_used' => 0,
                'upload_limit' => 0,
                'uploads_remaining' => 0,
                'message' => 'Please log in to upload tracks.',
            ];
        }

        return $this->canUpload($clientId);
    }

    /**
     * Record a successful upload for a client
     *
     * @param int $clientId
     * @return bool
     */
    public function recordUpload(int $clientId): bool
    {
        try {
            $year = now()->year;
            $month = now()->month;

            // Use upsert to increment or create
            $existing = DB::table('monthly_uploads')
                ->where('client_id', $clientId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($existing) {
                DB::table('monthly_uploads')
                    ->where('id', $existing->id)
                    ->update([
                        'uploads_count' => DB::raw('uploads_count + 1'),
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('monthly_uploads')->insert([
                    'client_id' => $clientId,
                    'year' => $year,
                    'month' => $month,
                    'uploads_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Log::info('Upload recorded', [
                'client_id' => $clientId,
                'year' => $year,
                'month' => $month,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to record upload', [
                'client_id' => $clientId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get the number of uploads used this month for a client
     *
     * @param int $clientId
     * @return int
     */
    public function getUploadsUsedThisMonth(int $clientId): int
    {
        $count = DB::table('monthly_uploads')
            ->where('client_id', $clientId)
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->value('uploads_count');

        return $count ?? 0;
    }

    /**
     * Get upload statistics for a client
     *
     * @param int $clientId
     * @return array
     */
    public function getUploadStats(int $clientId): array
    {
        $client = $this->getClient($clientId);
        $uploadsUsed = $this->getUploadsUsedThisMonth($clientId);
        $uploadLimit = $client->upload_limit ?? self::UPLOAD_LIMITS[$client->subscription_tier ?? 'free'] ?? 1;

        return [
            'tier' => $client->subscription_tier ?? 'free',
            'tier_name' => ucfirst($client->subscription_tier ?? 'free'),
            'billing_cycle' => $client->billing_cycle ?? 'monthly',
            'uploads_used' => $uploadsUsed,
            'upload_limit' => $uploadLimit,
            'uploads_remaining' => max(0, $uploadLimit - $uploadsUsed),
            'percentage_used' => $uploadLimit > 0 ? round(($uploadsUsed / $uploadLimit) * 100) : 100,
            'reset_date' => now()->startOfMonth()->addMonth()->format('F j, Y'),
            'show_annual_badge' => $client->show_annual_badge ?? false,
        ];
    }

    /**
     * Get upload history for a client (last 12 months)
     *
     * @param int $clientId
     * @return array
     */
    public function getUploadHistory(int $clientId): array
    {
        $history = DB::table('monthly_uploads')
            ->where('client_id', $clientId)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return $history->map(function ($record) {
            return [
                'year' => $record->year,
                'month' => $record->month,
                'month_name' => date('F', mktime(0, 0, 0, $record->month, 1)),
                'uploads_count' => $record->uploads_count,
            ];
        })->toArray();
    }

    /**
     * Reset monthly uploads for a client (used during plan changes or billing cycle reset)
     *
     * @param int $clientId
     * @return bool
     */
    public function resetMonthlyUploads(int $clientId): bool
    {
        try {
            // Don't actually delete - just record a new month
            // This preserves history
            Log::info('Monthly uploads would be reset', ['client_id' => $clientId]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to reset monthly uploads', [
                'client_id' => $clientId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Update client's upload limit based on their tier
     *
     * @param int $clientId
     * @param string $tier
     * @return bool
     */
    public function updateUploadLimit(int $clientId, string $tier): bool
    {
        $limit = self::UPLOAD_LIMITS[$tier] ?? 1;

        try {
            DB::table('clients')
                ->where('id', $clientId)
                ->update(['upload_limit' => $limit]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update upload limit', [
                'client_id' => $clientId,
                'tier' => $tier,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get upload limit for a specific tier
     *
     * @param string $tier
     * @return int
     */
    public static function getUploadLimitForTier(string $tier): int
    {
        return self::UPLOAD_LIMITS[$tier] ?? 1;
    }

    /**
     * Get client from database
     *
     * @param int $clientId
     * @return object|null
     */
    private function getClient(int $clientId): ?object
    {
        return DB::table('clients')->where('id', $clientId)->first();
    }
}
