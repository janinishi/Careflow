<?php
/**
 * CareFlow - Database Connection & Table Test Script
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h2>Testing Database Connection...</h2>";

try {
    $pdo = require_once __DIR__ . '/db.php';
    echo "<p style='color: green; font-weight: bold;'>✅ SUCCESS: Database connected successfully via PDO PostgreSQL!</p>";

    // ডেটাবেসের টেবিলগুলো চেক করা
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "<h3>Existing Tables in PostgreSQL:</h3>";
    if (empty($tables)) {
        echo "<p style='color: orange;'>⚠️ No tables found in the database yet. You need to import your PostgreSQL tables/schema.</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . htmlspecialchars($table) . "</li>";
        }
        echo "</ul>";
    }

} catch (Exception $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
}