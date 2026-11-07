<?php
declare(strict_types=1);

$dsn = "pgsql:host=dpg-dbnp6pec6qcc73egh3o0-a.singapore-postgres.render.com;port=5432;dbname=careflow_db_2f4d;sslmode=require";
$dbUser = "postgres";
$dbPass = "dtam4ZBwHTgrExwO6tMpWT4lyERvKeFK";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database Error: ' . $e->getMessage());
}

return $pdo;