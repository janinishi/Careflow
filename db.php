<?php
/**
 * CareFlow - Database Connection (PDO - PostgreSQL)
 * 
 * রেন্ডারের পোস্টগ্রেস ডেটাবেস কানেকশনের জন্য আপডেট করা কোড
 */

declare(strict_types=1);

// Render থেকে পাওয়া সঠিক ইউজারনেম (postgres) দিয়ে ইউআরএলটি আপডেট করা হলো
$database_url = getenv('DATABASE_URL') ?: 'postgresql://postgres:dtam4ZBwHTgrExwO6tMpWT4lyERvKeFK@dpg-dbnp6pec6qcc73egh3o0-a.singapore-postgres.render.com/careflow_db_2f4d';

$dbparts = parse_url($database_url);

$dbHost = $dbparts['host'] ?? 'localhost';
$dbPort = $dbparts['port'] ?? '5432';
$dbName = ltrim($dbparts['path'] ?? '', '/');
$dbUser = $dbparts['user'] ?? '';
$dbPass = $dbparts['pass'] ?? '';

// PostgreSQL-এর জন্য DSN (pgsql)
$dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";

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
    exit('Database connection failed. Please try again later.');
}

return $pdo;