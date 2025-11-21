<?php

// Quick script to check for chat/message database tables

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

echo "==============================================\n";
echo "CHAT/MESSAGING SYSTEM AUDIT\n";
echo "==============================================\n\n";

try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset=utf8mb4",
        $dbConfig['username'],
        $dbConfig['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✓ Database connection established\n\n";

    // Get all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Searching for chat/message related tables...\n";
    echo "----------------------------------------------\n";

    $chatTables = [];
    $keywords = ['message', 'chat', 'conversation', 'inbox', 'dm', 'direct'];

    foreach ($tables as $table) {
        $tableLower = strtolower($table);
        foreach ($keywords as $keyword) {
            if (strpos($tableLower, $keyword) !== false) {
                $chatTables[] = $table;
                break;
            }
        }
    }

    if (count($chatTables) > 0) {
        echo "✓ FOUND " . count($chatTables) . " chat-related table(s):\n\n";

        foreach ($chatTables as $table) {
            echo "TABLE: {$table}\n";

            // Get table structure
            $stmt = $pdo->query("DESCRIBE {$table}");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "  Columns:\n";
            foreach ($columns as $col) {
                echo "    - {$col['Field']} ({$col['Type']})\n";
            }

            // Get row count
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "  Row count: {$count}\n\n";
        }
    } else {
        echo "✗ NO chat/message tables found\n\n";
    }

    // Check for any user-to-user communication columns in other tables
    echo "Checking for messaging columns in other tables...\n";
    echo "----------------------------------------------\n";

    foreach ($tables as $table) {
        $stmt = $pdo->query("DESCRIBE {$table}");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($columns as $col) {
            $colLower = strtolower($col['Field']);
            foreach ($keywords as $keyword) {
                if (strpos($colLower, $keyword) !== false) {
                    echo "  Found: {$table}.{$col['Field']}\n";
                    break;
                }
            }
        }
    }

    echo "\n";
    echo "==============================================\n";
    echo "ALL DATABASE TABLES (" . count($tables) . " total):\n";
    echo "==============================================\n";
    foreach ($tables as $table) {
        echo "  - {$table}\n";
    }

} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "\nThis means the database is not accessible from this environment.\n";
    echo "You'll need to run this check manually or provide access to the database.\n";
}

echo "\n";
