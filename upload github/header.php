<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require __DIR__ . '/db.php';
$display_name = $_SESSION['full_name'] ?? $_SESSION['user_name'] ?? $_SESSION['name'] ?? 'Salma Mizan Nishi';

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user && !empty($user['full_name'])) {
            $display_name = $user['full_name'];
        }
    } catch (Exception $e) {
        // Fallback to session
    }
}

$self = dirname($_SERVER['PHP_SELF']);
$base = ($self === '/' || $self === '\\') ? '' : $self;
?>
<!-- Persistent Top Navigation Bar across all pages -->
<header class="bg-white shadow-sm border-b sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-2xl">🏥</span>
      <div>
        <h1 class="text-xl font-bold text-teal-600">CareFlow</h1>
        <p class="text-xs text-gray-500">স্বাগতম, <span class="font-bold text-teal-700"><?= htmlspecialchars($display_name) ?></span></p>
      </div>
    </div>

    <nav class="flex flex-wrap items-center gap-2 text-xs md:text-sm font-semibold">
      <a href="<?= $base ?>/patient-dashboard.php" class="px-3 py-1.5 bg-gray-100 hover:bg-teal-50 hover:text-teal-600 text-gray-700 rounded-lg transition">🏠 ড্যাশবোর্ড</a>
      <a href="<?= $base ?>/appointments.php" class="px-3 py-1.5 bg-gray-100 hover:bg-teal-50 hover:text-teal-600 text-gray-700 rounded-lg transition">🗓️ অ্যাপয়েন্টমেন্ট</a>
      <a href="<?= $base ?>/prescription.php" class="px-3 py-1.5 bg-gray-100 hover:bg-teal-50 hover:text-teal-600 text-gray-700 rounded-lg transition">💊 প্রেসক্রিপশন</a>
      <a href="<?= $base ?>/blood-donors.php" class="px-3 py-1.5 bg-gray-100 hover:bg-teal-50 hover:text-teal-600 text-gray-700 rounded-lg transition">🩸 ব্লাড ডোনার</a>
      <a href="<?= $base ?>/report-doctor.php" class="px-3 py-1.5 bg-gray-100 hover:bg-teal-50 hover:text-teal-600 text-gray-700 rounded-lg transition">🛡️ রিপোর্ট ডক্টর</a>
      <a href="<?= $base ?>/logout.php" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">🚪 লগআউট</a>
    </nav>
  </div>
</header>