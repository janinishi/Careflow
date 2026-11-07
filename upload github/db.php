<?php
/**
 * CareFlow - Database Connection (PDO)
 *
 * ব্যবহার: অন্য যেকোনো ফাইলে
 *   $pdo = require __DIR__ . '/db.php';
 *   $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
 *   $stmt->execute([':email' => $email]);
 *
 * গুরুত্বপূর্ণ: সবসময় prepared statement ব্যবহার করুন (SQL Injection থেকে বাঁচতে)।
 */

declare(strict_types=1);

// ---- কনফিগারেশন (XAMPP/phpMyAdmin ডিফল্ট) ----
// প্রোডাকশনে এগুলো সোর্স কোডে না রেখে environment variable ব্যবহার করুন।
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'careflow';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';          // XAMPP এ ডিফল্ট পাসওয়ার্ড ফাঁকা
$dbPort = getenv('DB_PORT') ?: '3306';

$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // এরর হলে Exception ছুঁড়বে
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // ডিফল্টে associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                    // আসল prepared statement (বেশি নিরাপদ)
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    // আসল এরর লগ ফাইলে যাবে, ইউজারকে দেখানো হবে না (পাসওয়ার্ড/হোস্ট ফাঁস রোধ)
    error_log('[CareFlow DB] Connection failed: ' . $e->getMessage());

    http_response_code(500);
    exit('Database connection failed. Please try again later.');
}

return $pdo;
