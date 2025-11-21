<?php

// Load database configuration from .env
$envFile = __DIR__ . '/.env';
$dbConfig = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'digiwaxx',
    'username' => 'root',
    'password' => ''
];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if ($key === 'DB_HOST') $dbConfig['host'] = $value;
            if ($key === 'DB_PORT') $dbConfig['port'] = $value;
            if ($key === 'DB_DATABASE') $dbConfig['database'] = $value;
            if ($key === 'DB_USERNAME') $dbConfig['username'] = $value;
            if ($key === 'DB_PASSWORD') $dbConfig['password'] = $value;
        }
    }
}

echo "Connecting to database: {$dbConfig['database']}@{$dbConfig['host']}:{$dbConfig['port']}\n";

try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset=utf8mb4",
        $dbConfig['username'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✓ Database connection established\n\n";

    // Migration SQL statements
    $migrations = [
        // Add review_notification_token column
        [
            'name' => 'Add review_notification_token to clients',
            'sql' => "ALTER TABLE clients ADD COLUMN review_notification_token VARCHAR(64) NULL UNIQUE AFTER trackReviewEmailsActivated",
            'check' => "SELECT COUNT(*) as count FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'clients' AND COLUMN_NAME = 'review_notification_token'"
        ],
        // Add email_weekly_digest column
        [
            'name' => 'Add email_weekly_digest to clients',
            'sql' => "ALTER TABLE clients ADD COLUMN email_weekly_digest TINYINT(1) DEFAULT 1 AFTER review_notification_token",
            'check' => "SELECT COUNT(*) as count FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'clients' AND COLUMN_NAME = 'email_weekly_digest'"
        ],
        // Add email_milestones column
        [
            'name' => 'Add email_milestones to clients',
            'sql' => "ALTER TABLE clients ADD COLUMN email_milestones TINYINT(1) DEFAULT 1 AFTER email_weekly_digest",
            'check' => "SELECT COUNT(*) as count FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'clients' AND COLUMN_NAME = 'email_milestones'"
        ],
        // Add email_newsletter column
        [
            'name' => 'Add email_newsletter to clients',
            'sql' => "ALTER TABLE clients ADD COLUMN email_newsletter TINYINT(1) DEFAULT 1 AFTER email_milestones",
            'check' => "SELECT COUNT(*) as count FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'clients' AND COLUMN_NAME = 'email_newsletter'"
        ],
        // Add email_preferences_updated_at column
        [
            'name' => 'Add email_preferences_updated_at to clients',
            'sql' => "ALTER TABLE clients ADD COLUMN email_preferences_updated_at TIMESTAMP NULL AFTER email_newsletter",
            'check' => "SELECT COUNT(*) as count FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'clients' AND COLUMN_NAME = 'email_preferences_updated_at'"
        ],
        // Add index on email
        [
            'name' => 'Add index on clients.email',
            'sql' => "ALTER TABLE clients ADD INDEX clients_email_index (email)",
            'check' => "SELECT COUNT(*) as count FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'clients' AND INDEX_NAME = 'clients_email_index'"
        ],
        // Create generated_reports table
        [
            'name' => 'Create generated_reports table',
            'sql' => "CREATE TABLE IF NOT EXISTS generated_reports (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                client_id BIGINT UNSIGNED NOT NULL,
                track_id BIGINT UNSIGNED NULL,
                report_type VARCHAR(50) NOT NULL COMMENT 'validation, demand, regional, format, full',
                format VARCHAR(10) NOT NULL COMMENT 'pdf, csv',
                date_range_start DATE NULL,
                date_range_end DATE NULL,
                file_path VARCHAR(255) NOT NULL,
                generated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                expires_at TIMESTAMP NULL,
                download_count INT NOT NULL DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX generated_reports_client_id_index (client_id),
                INDEX generated_reports_track_id_index (track_id),
                INDEX generated_reports_generated_at_index (generated_at),
                INDEX generated_reports_expires_at_index (expires_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            'check' => "SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'generated_reports'"
        ],
        // Create email_notification_logs table
        [
            'name' => 'Create email_notification_logs table',
            'sql' => "CREATE TABLE IF NOT EXISTS email_notification_logs (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                client_id BIGINT UNSIGNED NOT NULL,
                track_id BIGINT UNSIGNED NULL,
                review_id BIGINT UNSIGNED NULL,
                notification_type VARCHAR(50) NOT NULL COMMENT 'review, weekly_digest, milestone, newsletter',
                status VARCHAR(20) NOT NULL COMMENT 'sent, failed, bounced, opened, clicked',
                recipient_email VARCHAR(255) NOT NULL,
                error_message TEXT NULL,
                sent_at TIMESTAMP NULL,
                opened_at TIMESTAMP NULL,
                clicked_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                INDEX email_notification_logs_client_id_index (client_id),
                INDEX email_notification_logs_track_id_index (track_id),
                INDEX email_notification_logs_review_id_index (review_id),
                INDEX email_notification_logs_notification_type_index (notification_type),
                INDEX email_notification_logs_status_index (status),
                INDEX email_notification_logs_sent_at_index (sent_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            'check' => "SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = '{$dbConfig['database']}' AND TABLE_NAME = 'email_notification_logs'"
        ]
    ];

    $successCount = 0;
    $skippedCount = 0;
    $errorCount = 0;

    foreach ($migrations as $migration) {
        echo "Running: {$migration['name']}\n";

        // Check if migration already applied
        $stmt = $pdo->query($migration['check']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            echo "  ⊙ Already exists, skipping\n\n";
            $skippedCount++;
            continue;
        }

        try {
            $pdo->exec($migration['sql']);
            echo "  ✓ Success\n\n";
            $successCount++;
        } catch (PDOException $e) {
            echo "  ✗ Error: " . $e->getMessage() . "\n\n";
            $errorCount++;
        }
    }

    echo "==========================================\n";
    echo "Migration Summary:\n";
    echo "  ✓ Applied:  {$successCount}\n";
    echo "  ⊙ Skipped:  {$skippedCount}\n";
    echo "  ✗ Errors:   {$errorCount}\n";
    echo "==========================================\n\n";

    // Verification queries
    echo "VERIFICATION RESULTS:\n";
    echo "==========================================\n\n";

    // Check clients table columns
    echo "1. New columns in clients table:\n";
    $stmt = $pdo->query("DESCRIBE clients");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $newColumns = ['review_notification_token', 'email_weekly_digest', 'email_milestones', 'email_newsletter', 'email_preferences_updated_at'];
    foreach ($columns as $col) {
        if (in_array($col['Field'], $newColumns)) {
            echo "  ✓ {$col['Field']} ({$col['Type']})\n";
        }
    }
    echo "\n";

    // Check generated_reports table
    echo "2. generated_reports table structure:\n";
    $stmt = $pdo->query("DESCRIBE generated_reports");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "  ✓ Table exists with " . count($columns) . " columns\n";
    echo "\n";

    // Check email_notification_logs table
    echo "3. email_notification_logs table structure:\n";
    $stmt = $pdo->query("DESCRIBE email_notification_logs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "  ✓ Table exists with " . count($columns) . " columns\n";
    echo "\n";

    // Check indexes
    echo "4. Indexes created:\n";
    $stmt = $pdo->query("SHOW INDEX FROM clients WHERE Key_name = 'clients_email_index'");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($indexes) > 0) {
        echo "  ✓ clients_email_index created\n";
    }

    $stmt = $pdo->query("SHOW INDEX FROM generated_reports");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $indexNames = array_unique(array_column($indexes, 'Key_name'));
    echo "  ✓ generated_reports has " . count($indexNames) . " indexes\n";

    $stmt = $pdo->query("SHOW INDEX FROM email_notification_logs");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $indexNames = array_unique(array_column($indexes, 'Key_name'));
    echo "  ✓ email_notification_logs has " . count($indexNames) . " indexes\n";
    echo "\n";

    echo "==========================================\n";
    echo "✅ MIGRATION COMPLETE!\n";
    echo "==========================================\n";

} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
