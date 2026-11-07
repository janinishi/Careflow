<?php
/**
 * CareFlow - Database Connection (PDO - PostgreSQL)
 * 
 * রেন্ডারের পোস্টগ্রেস ডেটাবেসের জন্য আপডেট করা কোড
 */

declare(strict_types=1);

$database_url = getenv('DATABASE_URL') ?: 'postgresql://postgres:dtam4ZBwHTgrExwO6tMpWT4lyERvKeFK@dpg-dbnp6pec6qcc73egh3o0-a.singapore-postgres.render.com/careflow_db_2f4d';

$dbparts = parse_url($database_url);

$dbHost = $dbparts['host'] ?? 'localhost';
$dbPort = $dbparts['port'] ?? '5432';
$dbName = ltrim($dbparts['path'] ?? '', '/');
$dbUser = $dbparts['user'] ?? '';
$dbPass = $dbparts['pass'] ?? '';

// PostgreSQL-এর জন্য DSN-এ sslmode=require যোগ করা হলো
$dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName};sslmode=require";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    error_log('[CareFlow DB] Connection failed: ' . $e->getMessage());
    http_response_code(500);
    // আসল সমস্যাটি স্ক্রিনে দেখতে পাওয়ার জন্য
    exit('Database Error: ' . $e->getMessage());
}

return $pdo;