<?php
// CareFlow - Fake Doctor Reporting System
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
  <title>Fake Doctor Reporting System - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
  
  <!-- Header Section with Language Button -->
  <header class="bg-white shadow-sm border-b border-gray-100">
    <nav class="max-w-6xl mx-auto flex items-center justify-between px-4 py-3">
      <a href="index.html" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>
      <div class="flex items-center gap-3 text-sm font-medium">
        <a href="index.html" class="px-3 py-2 hover:text-teal-600">Home</a>
        <a href="patient-dashboard.html" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">Dashboard</a>
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 font-semibold transition">
          বাংলা
        </button>
      </div>
    </nav>
  </header>

  <main class="max-w-5xl mx-auto px-4 py-10 space-y-8">

    <!-- Page Title -->
    <div>
      <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">🛡️ Fake Doctor Reporting System</h1>
      <p class="text-gray-600 mt-1">Help protect patients by reporting fraudulent or unverified medical practitioners.</p>
    </div>

    <!-- Submit Report Form -->
    <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
      <h2 class="text-xl font-bold mb-4 text-gray-900">Submit a Report</h2>
      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold mb-1">Doctor's Name / Chamber Name</label>
          <input type="text" name="doc_name" required placeholder="Enter doctor or clinic name" class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Claimed BMDC Reg. No (if any)</label>
          <input type="text" name="bmdc" placeholder="e.g. A-12345 or Invalid" class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Description / Reason for Suspicion</label>
          <textarea name="desc" rows="3" required placeholder="Describe why you suspect this doctor..." class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
        </div>
        <button type="submit" class="px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-sm">Submit Report</button>
      </form>
    </section>

    <!-- Verification & Report Status Table -->
    <section class="bg-[#fffdf5] rounded-2xl shadow-sm border border-amber-200 overflow-hidden p-3">
      <div class="px-4 py-3 border-b border-amber-100">
        <h2 class="text-xl font-bold text-gray-900">Verification & Report Status</h2>
      </div>
      <div class="overflow-x-auto rounded-xl bg-white border border-amber-100 mt-2">
        <table class="w-full text-sm text-left">
          <thead class="bg-[#fffbe8] text-[#78350f] uppercase text-xs font-bold border-b border-amber-100">
            <tr>
              <th class="px-6 py-4">Doctor Name</th>
              <th class="px-6 py-4">BMDC Reg. No.</th>
              <th class="px-6 py-4">Specialization</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4">Action</th>
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
</body>
</html>