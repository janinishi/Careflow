<?php
/**
 * CareFlow - Database Connection (PDO - PostgreSQL)
 */

declare(strict_types=1);

// Render Environment Variables অথবা ডিফল্ট ফলব্যাক ভ্যালু ব্যবহার করা হয়েছে
$host = getenv('DB_HOST') ?: "dpg-dbnp6pec6qcc73egh3o0-a.singapore-postgres.render.com";
$port = getenv('DB_PORT') ?: "5432";
$dbname = getenv('DB_NAME') ?: "careflow_db_2f4d";
$username = getenv('DB_USER') ?: "careflow_db_2f4d_user";
$password = getenv('DB_PASSWORD') ?: "dtam4ZBwHTgrExwO6tMpWT4lyERvKeFK";

$dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo "<h3>Database Connection Error:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit();
}

return $pdo;