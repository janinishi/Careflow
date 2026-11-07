<?php
/**
 * CareFlow - Session Helper
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ইউজার লগইন করা আছে কিনা চেক করার ফাংশন
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// নির্দিষ্ট রোল চেক করার ফাংশন (admin, doctor, patient)
function checkRole(string $requiredRole): void {
    if (!isLoggedIn() || $_SESSION['role'] !== $requiredRole) {
        header('Location: login.php?error=unauthorized');
        exit;
    }
}