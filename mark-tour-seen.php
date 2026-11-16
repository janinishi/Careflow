<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE users SET has_seen_guide = 1 WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>