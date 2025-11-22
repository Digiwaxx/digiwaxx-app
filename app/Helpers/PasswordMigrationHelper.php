<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Password Migration Helper
 *
 * Handles migration from legacy MD5 password hashing to secure bcrypt.
 *
 * SECURITY NOTE: MD5 is cryptographically broken and passwords can be
 * cracked in milliseconds. This helper provides a migration path to bcrypt
 * while maintaining backward compatibility during the transition.
 */
class PasswordMigrationHelper
{
    /**
     * Hash a password securely using bcrypt
     *
     * @param string $password Plaintext password
     * @return string Bcrypt hashed password
     */
    public static function hashPassword($password)
    {
        return Hash::make($password);
    }

    /**
     * Verify a password against both MD5 (legacy) and bcrypt hashes
     * Automatically upgrades MD5 passwords to bcrypt on successful verification
     *
     * @param string $plainPassword Plaintext password from user
     * @param string $storedHash Hash stored in database
     * @param string $table Database table (members, clients, admins)
     * @param int $userId User ID for password upgrade
     * @return bool True if password matches
     */
    public static function verifyAndUpgrade($plainPassword, $storedHash, $table, $userId)
    {
        // First, try bcrypt verification (for already-migrated passwords)
        if (Hash::check($plainPassword, $storedHash)) {
            return true;
        }

        // Fall back to MD5 for legacy passwords
        $md5Hash = md5($plainPassword);
        if ($md5Hash === $storedHash) {
            // Password is correct but using weak MD5
            // Auto-upgrade to bcrypt
            self::upgradePassword($plainPassword, $table, $userId);

            Log::info('Password auto-upgraded from MD5 to bcrypt', [
                'table' => $table,
                'user_id' => $userId
            ]);

            return true;
        }

        return false;
    }

    /**
     * Upgrade a user's password from MD5 to bcrypt
     *
     * @param string $plainPassword Plaintext password
     * @param string $table Database table
     * @param int $userId User ID
     * @return bool Success status
     */
    private static function upgradePassword($plainPassword, $table, $userId)
    {
        try {
            $newHash = self::hashPassword($plainPassword);

            DB::table($table)
                ->where('id', $userId)
                ->update(['pword' => $newHash]);

            return true;
        } catch (\Exception $e) {
            Log::error('Password upgrade failed', [
                'error' => $e->getMessage(),
                'table' => $table,
                'user_id' => $userId
            ]);
            return false;
        }
    }

    /**
     * Check if a password hash is MD5 (legacy) or bcrypt
     *
     * @param string $hash Password hash
     * @return string 'md5', 'bcrypt', or 'unknown'
     */
    public static function getHashType($hash)
    {
        // Bcrypt hashes start with $2y$ or $2a$ and are 60 characters
        if (preg_match('/^\$2[ay]\$.{56}$/', $hash)) {
            return 'bcrypt';
        }

        // MD5 hashes are 32 hexadecimal characters
        if (preg_match('/^[a-f0-9]{32}$/i', $hash)) {
            return 'md5';
        }

        return 'unknown';
    }

    /**
     * Get statistics on password hash types in the database
     *
     * @return array Statistics array
     */
    public static function getPasswordStats()
    {
        $stats = [];

        $tables = ['members', 'clients', 'admins'];

        foreach ($tables as $table) {
            try {
                $total = DB::table($table)->count();

                // Count MD5 hashes (32 characters)
                $md5Count = DB::table($table)
                    ->whereRaw('LENGTH(pword) = 32')
                    ->count();

                // Count bcrypt hashes (60 characters, starts with $2)
                $bcryptCount = DB::table($table)
                    ->whereRaw("pword LIKE '\$2%'")
                    ->whereRaw('LENGTH(pword) = 60')
                    ->count();

                $stats[$table] = [
                    'total' => $total,
                    'md5' => $md5Count,
                    'bcrypt' => $bcryptCount,
                    'unknown' => $total - $md5Count - $bcryptCount,
                    'migration_progress' => $total > 0 ? round(($bcryptCount / $total) * 100, 2) : 0
                ];
            } catch (\Exception $e) {
                $stats[$table] = [
                    'error' => $e->getMessage()
                ];
            }
        }

        return $stats;
    }

    /**
     * Bulk migrate all MD5 passwords to bcrypt
     *
     * WARNING: This requires knowing plaintext passwords, which we don't have.
     * Use verifyAndUpgrade() instead to migrate passwords as users log in.
     *
     * This method is here for documentation purposes only.
     *
     * @deprecated Cannot be implemented - plaintext passwords not available
     */
    public static function bulkMigrate()
    {
        throw new \Exception(
            'Bulk password migration is not possible. ' .
            'Passwords will be automatically upgraded to bcrypt when users log in. ' .
            'Use verifyAndUpgrade() during authentication instead.'
        );
    }
}
