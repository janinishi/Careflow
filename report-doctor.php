<?php
// CareFlow - Fake Doctor Reporting System
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// সেশন বা ডাটাবেস থেকে ইউজারের নাম নিশ্চিত করা
$display_name = $_SESSION['full_name'] ?? $_SESSION['name'] ?? 'Patient';
if (!empty($_SESSION['user_id']) && isset($pdo)) {
    try {
        $stmt = $pdo->prepare("SELECT full_name, name FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) {
            $display_name = $user['full_name'] ?? $user['name'] ?? $display_name;
        }
    } catch (Exception $e) {
        // Fallback
    }
}
$first_letter = strtoupper(mb_substr($display_name, 0, 1));

$reports = [
    ["name" => "Dr. Rahman", "bmdc" => "A-54321", "specialty" => "Cardiologist", "status" => "Verified✓", "action" => "Active"],
    ["name" => "Fake Doctor (Suspect)", "bmdc" => "Invalid", "specialty" => "General Practitioner", "status" => "Reported 🚩", "action" => "Under Review"],
    ["name" => "Dr. M. A. Wahab", "bmdc" => "A-54321", "specialty" => "Cardiology", "status" => "Verified✓", "action" => "Active"],
    ["name" => "Dr. Pran Gopal Datta", "bmdc" => "A-12890", "specialty" => "ENT", "status" => "Verified✓", "action" => "Active"],
    ["name" => "Dr. Shafiqul Islam", "bmdc" => "Not Provided", "specialty" => "Surgery Fraud", "status" => "Not Verified", "action" => "Under Investigation"]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doc_name = $_POST['doc_name'] ?? '';
    $bmdc = $_POST['bmdc'] ?? '';
    $desc = $_POST['desc'] ?? '';
    if (!empty($doc_name)) {
        array_unshift($reports, [
            "name" => htmlspecialchars($doc_name),
            "bmdc" => htmlspecialchars($bmdc ?: 'Invalid'),
            "specialty" => "Unverified / Suspect",
            "status" => "Reported 🚩",
            "action" => "Under Review"
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title data-i18n="report.title">Fake Doctor Reporting System - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

  <!-- Top Bar with Profile Dropdown in Corner -->
  <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3.5">
      <!-- Logo -->
      <a href="index.html" class="text-2xl font-bold text-teal-600 tracking-tight">Care<span class="text-gray-800">Flow</span></a>
      
      <!-- Center Nav Links -->
      <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
        <a href="index.html" class="hover:text-teal-600 transition" data-i18n="nav.home">Home</a>
        <a href="booking.html" class="hover:text-teal-600 transition" data-i18n="nav.book">Book Appointment</a>
        <a href="blood-donors.php" class="hover:text-teal-600 transition" data-i18n="side.blood">Blood Donors</a>
        <a href="report-doctor.php" class="text-red-600 font-semibold transition" data-i18n="nav.report">Report Doctor</a>
      </div>

      <!-- Right Corner Profile & Language Toggle -->
      <div class="flex items-center gap-4">
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 text-sm font-medium border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition cursor-pointer">
          বাংলা
        </button>

        <!-- Profile Dropdown Menu -->
        <div class="relative">
          <button onclick="toggleDropdown()" class="flex items-center gap-3 focus:outline-none bg-gray-50 hover:bg-gray-100 p-1.5 rounded-full border border-gray-200 transition cursor-pointer">
            <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold text-sm">
              <?= htmlspecialchars($first_letter) ?>
            </div>
            <span class="hidden sm:block text-sm font-semibold text-gray-800 pr-2"><?= htmlspecialchars($display_name) ?> ▼</span>
          </button>

          <!-- Dropdown Box -->
          <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 text-sm z-50">
            <a href="patient-dashboard.php" class="block px-4 py-2 text-gray-700 hover:bg-teal-50 hover:text-teal-700 font-medium">🏠 Dashboard</a>
            <div class="border-t border-gray-100 my-1"></div>
            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50 font-medium">🚪 Logout</a>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-5xl mx-auto px-4 md:px-8 py-10 space-y-8 w-full flex-1">

    <!-- Page Title -->
    <div>
      <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2" data-i18n="rep.h1">🛡️ Fake Doctor Reporting System</h1>
      <p class="text-gray-600 mt-1" data-i18n="rep.sub">Help protect patients by reporting fraudulent or unverified medical practitioners.</p>
    </div>

    <!-- Submit Report Form -->
    <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
      <h2 class="text-xl font-bold mb-4 text-gray-900" data-i18n="rep.formTitle">Submit a Report</h2>
      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold mb-1" data-i18n="rep.docName">Doctor's Name / Chamber Name</label>
          <input type="text" name="doc_name" required placeholder="Enter doctor or clinic name" data-i18n-placeholder="rep.docPh" class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1" data-i18n="rep.bmdc">Claimed BMDC Reg. No (if any)</label>
          <input type="text" name="bmdc" placeholder="e.g. A-12345 or Invalid" data-i18n-placeholder="rep.bmdcPh" class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1" data-i18n="rep.desc">Description / Reason for Suspicion</label>
          <textarea name="desc" rows="3" required placeholder="Describe why you suspect this doctor..." data-i18n-placeholder="rep.descPh" class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
        </div>
        <button type="submit" class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-sm" data-i18n="rep.submitBtn">Submit Report</button>
      </form>
    </section>

    <!-- Verification & Report Status Table -->
    <section class="bg-[#fffdf5] rounded-2xl shadow-sm border border-amber-200 overflow-hidden p-3">
      <div class="px-4 py-3 border-b border-amber-100">
        <h2 class="text-xl font-bold text-gray-900" data-i18n="rep.tableTitle">Verification & Report Status</h2>
      </div>
      <div class="overflow-x-auto rounded-xl bg-white border border-amber-100 mt-2">
        <table class="w-full text-sm text-left">
          <thead class="bg-[#fffbe8] text-[#78350f] uppercase text-xs font-bold border-b border-amber-100">
            <tr>
              <th class="px-6 py-4" data-i18n="th.doctor">Doctor Name</th>
              <th class="px-6 py-4" data-i18n="th.bmdc">BMDC Reg. No.</th>
              <th class="px-6 py-4" data-i18n="th.spec">Specialization</th>
              <th class="px-6 py-4" data-i18n="th.status">Status</th>
              <th class="px-6 py-4" data-i18n="th.action">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($reports as $r): ?>
              <tr class="hover:bg-amber-50/30">
                <td class="px-6 py-4 font-bold text-gray-900"><?= $r['name'] ?></td>
                <td class="px-6 py-4 <?= $r['bmdc']=='Invalid' ? 'text-red-600 font-semibold' : 'text-gray-600' ?>"><?= $r['bmdc'] ?></td>
                <td class="px-6 py-4 text-gray-700"><?= $r['specialty'] ?></td>
                <td class="px-6 py-4">
                  <?php if ($r['status'] === 'Not Verified'): ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><?= $r['status'] ?></span>
                  <?php elseif (strpos($r['status'], 'Verified') !== false): ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800"><?= $r['status'] ?></span>
                  <?php else: ?>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-700"><?= $r['status'] ?></span>
                  <?php endif; ?>
                </td>
                <td class="px-6 py-4 font-bold <?= $r['action']=='Active' ? 'text-gray-800' : 'text-red-700' ?>">
                  <?= $r['action'] ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

  </main>
  
  <script src="i18n.js"></script>
  <script>
    // Toggle Profile Dropdown
    function toggleDropdown() {
      const dropdown = document.getElementById('profile-dropdown');
      dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
      const dropdown = document.getElementById('profile-dropdown');
      const btn = dropdown.previousElementSibling;
      if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });

    // Update Language Button Text
    document.addEventListener("langchange", function () {
      const btn = document.getElementById("lang-btn");
      if (btn && typeof isBn === "function") {
        btn.textContent = isBn() ? "English" : "বাংলা";
      }
    });

    document.addEventListener("DOMContentLoaded", function () {
      const btn = document.getElementById("lang-btn");
      if (btn && typeof isBn === "function") {
        btn.textContent = isBn() ? "English" : "বাংলা";
      }
    });
  </script>
</body>
</html>