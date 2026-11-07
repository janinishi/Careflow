<?php
$dsn = "pgsql:host=dpg-dbnp6pec6qcc73egh3o0-a.singapore-postgres.render.com;port=5432;dbname=careflow_db_2f4d;sslmode=require";
$username = "postgres";
$password = "dtam4ZBwHTgrExwO6tMpWT4lyERvKeFK";

try {
    $pdo = new PDO($dsn, $username, $password);
    echo "SUCCESS: Database connected successfully!";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}