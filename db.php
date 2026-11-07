<?php
/**
 * CareFlow - Database Connection (PDO - PostgreSQL)
 */

declare(strict_types=1);

$host = "dpg-dbnp6pec6qcc73egh3o0-a.singapore-postgres.render.com";
$port = "5432";
$dbname = "careflow_db_2f4d";
$username = "postgres";
$password = "dtam4ZBwHTgrExwO6tMpWT4lyERvKeFK";

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