#!/usr/bin/env php
<?php
/**
 * Automated SQL Injection Fix Script
 * Converts dangerous string concatenation to parameterized queries
 */

function fixSqlInjection($filePath) {
    echo "Processing: $filePath\n";

    $content = file_get_contents($filePath);
    $original = $content;
    $fixCount = 0;

    // Pattern 1: Simple single variable in WHERE clause
    // DB::select("... where column = '$variable'")
    // -> DB::select("... where column = ?", [$variable])
    $pattern1 = '/DB::select\("([^"]*?)where\s+(\w+)\s*=\s*\'?\\\$([a-zA-Z_]\w*?)\'?"\)/s';
    $content = preg_replace_callback($pattern1, function($matches) use (&$fixCount) {
        $fixCount++;
        $sql = str_replace('$' . $matches[3], '?', $matches[0]);
        $sql = str_replace('")', '", [$' . $matches[3] . '])', $sql);
        return $sql;
    }, $content);

    // Pattern 2: Multiple string concatenations
    // DB::select("... where id = '" . $id . "'")
    // -> DB::select("... where id = ?", [$id])
    $pattern2 = '/DB::select\("([^"]*?)\s*\'\s*\.\s*\$([a-zA-Z_]\w*?)\s*\.\s*\'([^"]*?)"\)/s';
    $content = preg_replace_callback($pattern2, function($matches) use (&$fixCount) {
        $fixCount++;
        return 'DB::select("' . $matches[1] . '?' . $matches[3] . '", [$' . $matches[2] . '])';
    }, $content);

    if ($content !== $original) {
        // Create backup
        $backupPath = $filePath . '.backup_' . date('YmdHis');
        copy($filePath, $backupPath);

        file_put_contents($filePath, $content);
        echo "  ✓ Fixed $fixCount instances\n";
        echo "  ✓ Backup created: $backupPath\n";
        return $fixCount;
    } else {
        echo "  ℹ No automatic fixes applied\n";
        return 0;
    }
}

// Files to process
$files = [
    __DIR__ . '/Models/MemberAllDB.php',
    __DIR__ . '/Models/ClientAllDB.php',
    __DIR__ . '/Models/Admin.php',
];

$totalFixes = 0;
foreach ($files as $file) {
    if (file_exists($file)) {
        $totalFixes += fixSqlInjection($file);
    } else {
        echo "WARNING: File not found: $file\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total automatic fixes applied: $totalFixes\n";
echo "\nNOTE: This script handles common patterns only.\n";
echo "Manual review and testing is REQUIRED before deployment.\n";
echo "Complex queries with multiple variables need manual fixing.\n";
