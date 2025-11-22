<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class GeolocationHelper
{
    /**
     * Securely fetch geolocation data for an IP address
     * SECURITY: Validates IP and prevents SSRF attacks
     *
     * @param string $ip IP address to lookup
     * @return array Array with 'countryCode' and 'countryName' keys
     */
    public static function getGeolocation($ip)
    {
        $result = [
            'countryCode' => '',
            'countryName' => ''
        ];

        // SECURITY: Validate IP address and reject private/reserved ranges
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            Log::warning('Geolocation: Invalid IP address rejected', ['ip' => $ip]);
            return $result;
        }

        try {
            // Set timeout and proper headers to prevent hanging requests
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5, // 5 second timeout
                    'method' => 'GET',
                    'header' => "User-Agent: Digiwaxx/1.0\r\n"
                ]
            ]);

            // Make request with validated IP (URL-encoded for safety)
            $response = @file_get_contents(
                "http://www.geoplugin.net/json.gp?ip=" . urlencode($ip),
                false,
                $context
            );

            if ($response === false) {
                Log::warning('Geolocation: API request failed', ['ip' => $ip]);
                return $result;
            }

            $ip_data = json_decode($response);

            // Validate response structure
            if ($ip_data &&
                isset($ip_data->geoplugin_countryCode) &&
                isset($ip_data->geoplugin_countryName) &&
                $ip_data->geoplugin_countryName !== null) {

                $result['countryCode'] = $ip_data->geoplugin_countryCode;
                $result['countryName'] = $ip_data->geoplugin_countryName;
            }

        } catch (\Exception $e) {
            Log::error('Geolocation: Exception occurred', [
                'error' => $e->getMessage(),
                'ip' => $ip
            ]);
        }

        return $result;
    }

    /**
     * Get client IP from request headers
     * Handles X-Forwarded-For and other proxy headers
     *
     * @param string $clientIp HTTP_CLIENT_IP header value
     * @param string $forward HTTP_X_FORWARDED_FOR header value
     * @param string $remote REMOTE_ADDR value
     * @return string Best-guess client IP address
     */
    public static function getClientIp($clientIp, $forward, $remote)
    {
        // Check each IP source in order of reliability
        if (filter_var($clientIp, FILTER_VALIDATE_IP)) {
            return $clientIp;
        }

        if (filter_var($forward, FILTER_VALIDATE_IP)) {
            return $forward;
        }

        return $remote;
    }
}
