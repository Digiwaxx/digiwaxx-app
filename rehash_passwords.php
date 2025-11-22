<?php
/**
 * Password Rehash Script
 *
 * This script rehashes all passwords in the clients and members tables to bcrypt.
 *
 * IMPORTANT: Since MD5 hashes cannot be reversed, this script sets a temporary
 * password for all users. Users will need to reset their passwords after running this.
 *
 * Usage: php rehash_passwords.php [--dry-run] [--password=YOUR_TEMP_PASSWORD]
 *
 * Options:
 *   --dry-run    Show what would be changed without making changes
 *   --password   Set a custom temporary password (default: 'TempPass123!')
 */

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Parse command line arguments
$dryRun = in_array('--dry-run', $argv);
$tempPassword = 'TempPass123!';

foreach ($argv as $arg) {
    if (strpos($arg, '--password=') === 0) {
        $tempPassword = substr($arg, 11);
    }
}

echo "===========================================\n";
echo "Password Rehash Script\n";
echo "===========================================\n\n";

if ($dryRun) {
    echo "*** DRY RUN MODE - No changes will be made ***\n\n";
}

// Generate bcrypt hash for the temporary password
$bcryptHash = Hash::make($tempPassword);

echo "Temporary password: {$tempPassword}\n";
echo "Bcrypt hash: {$bcryptHash}\n\n";

// Function to check if a password is already bcrypt
function isBcrypt($hash) {
    return preg_match('/^\$2[ayb]\$.{56}$/', $hash);
}

// Function to rehash passwords in a table
function rehashTable($tableName, $bcryptHash, $dryRun) {
    echo "Processing table: {$tableName}\n";
    echo str_repeat('-', 40) . "\n";

    // Get all users
    $users = DB::table($tableName)->select('id', 'uname', 'email', 'pword')->get();

    $total = count($users);
    $alreadyBcrypt = 0;
    $needsRehash = 0;
    $updated = 0;

    foreach ($users as $user) {
        if (isBcrypt($user->pword)) {
            $alreadyBcrypt++;
            echo "  [SKIP] User #{$user->id} ({$user->uname}) - Already bcrypt\n";
        } else {
            $needsRehash++;
            if (!$dryRun) {
                DB::table($tableName)
                    ->where('id', $user->id)
                    ->update(['pword' => $bcryptHash]);
                $updated++;
                echo "  [UPDATED] User #{$user->id} ({$user->uname}) - Password rehashed\n";
            } else {
                echo "  [WOULD UPDATE] User #{$user->id} ({$user->uname}) - Needs rehash\n";
            }
        }
    }

    echo "\nSummary for {$tableName}:\n";
    echo "  Total users: {$total}\n";
    echo "  Already bcrypt: {$alreadyBcrypt}\n";
    echo "  Needs rehash: {$needsRehash}\n";
    if (!$dryRun) {
        echo "  Updated: {$updated}\n";
    }
    echo "\n";

    return [
        'total' => $total,
        'already_bcrypt' => $alreadyBcrypt,
        'needs_rehash' => $needsRehash,
        'updated' => $updated
    ];
}

// Process both tables
$clientStats = rehashTable('clients', $bcryptHash, $dryRun);
$memberStats = rehashTable('members', $bcryptHash, $dryRun);

// Final summary
echo "===========================================\n";
echo "FINAL SUMMARY\n";
echo "===========================================\n";
echo "Clients:\n";
echo "  Total: {$clientStats['total']}, Already bcrypt: {$clientStats['already_bcrypt']}, Rehashed: {$clientStats['updated']}\n";
echo "Members:\n";
echo "  Total: {$memberStats['total']}, Already bcrypt: {$memberStats['already_bcrypt']}, Rehashed: {$memberStats['updated']}\n";

if ($dryRun) {
    echo "\n*** This was a DRY RUN - No changes were made ***\n";
    echo "Run without --dry-run to apply changes.\n";
} else {
    echo "\n*** IMPORTANT ***\n";
    echo "All non-bcrypt passwords have been set to: {$tempPassword}\n";
    echo "Please notify users to reset their passwords!\n";
}

echo "\nDone.\n";
