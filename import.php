<?php
$pdo = require 'db.php';

$sql = file_get_contents('db.sql');
if ($sql === false) {
    die("Could not read db.sql file.");
}

try {
    $pdo->exec($sql);
    echo "<h3>✅ Database tables imported successfully!</h3>";
} catch (PDOException $e) {
    echo "<h3>❌ Error importing database:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}