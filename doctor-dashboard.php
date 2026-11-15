<?php
/**
 * CareFlow - Doctor Dashboard with In-Page Live Tour
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header('Location: login.php');
    exit;
}

$display_name = $_SESSION['full_name'] ?? $_SESSION['name'] ?? 'Doctor';
$first_letter = strtoupper(mb_substr($display_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Dashboard - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Hind Siliguri', system-ui, sans-serif; }
    .tour-highlight {
      ring: 4px solid #0d9488 !important;
      background-color: #ccfbf1 !important;
      transition: all 0.3s ease;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col md:flex-row">

  <!-- Sidebar -->
  <aside id="sidebar" class="hidden md:block w-full md:w-64 bg-white border-r border-gray-200 shrink-0 min-h-screen">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <a href="index.php" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>
    </div>

    <nav class="p-4 space-y-1 text-sm font-medium">
      <a href="doctor-dashboard.php" id="doc-dash" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-teal-50 text-teal-700 font-semibold">🩺 Dashboard</a>
      <a href="doctor-appointments.php" id="doc-appts" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700">📋 Patient Appointments</a>
      <a href="write-prescription.php" id="doc-presc" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700">✍️ Write Prescription</a>
      <a href="doctor-profile.php" id="doc-profile" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700">⚙️ Profile & Schedule</a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Area -->
  <div class="flex-1 flex flex-col min-w-0">
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-4 flex items-center justify-between sticky top-0 z-10">
      <h1 class="text-xl font-bold text-gray-800">Doctor Portal</h1>
      <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600 font-medium">Dr. <strong><?= htmlspecialchars($display_name) ?></strong></span>
        <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold"><?= htmlspecialchars($first_letter) ?></div>
      </div>
    </header>

    <main class="p-4 md:p-8 space-y-8 flex-1">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Welcome, Dr. <?= htmlspecialchars($display_name) ?> 🩺</h2>
        <p class="text-gray-600 text-sm mt-1">Manage your patients and chamber schedules here.</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase">Today's Patients</p>
          <p class="text-3xl font-bold text-teal-600 mt-2">5</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase">Total Prescriptions</p>
          <p class="text-3xl font-bold text-blue-600 mt-2">42</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase">Chamber Status</p>
          <p class="text-3xl font-bold text-green-600 mt-2">Active</p>
        </div>
      </div>
    </main>
  </div>

  <!-- Live Tour Widget -->
  <div id="liveTourModal" class="fixed bottom-6 right-6 z-50 bg-white rounded-2xl shadow-2xl border border-teal-200 max-w-sm w-full p-5 space-y-3">
    <div class="flex items-center justify-between border-b pb-2">
      <span id="tourStepIndicator" class="text-xs font-bold px-2.5 py-0.5 bg-teal-50 text-teal-700 rounded-full">Step 1 of 4</span>
      <button onclick="closeLiveTour()" class="text-xs text-gray-400 hover:text-gray-600 font-medium">Skip Tour ✕</button>
    </div>
    <div class="space-y-1.5">
      <h4 id="tourTitle" class="font-bold text-gray-800 text-base">Doctor Dashboard</h4>
      <p id="tourDesc" class="text-xs text-gray-600 leading-relaxed"></p>
    </div>
    <div class="flex items-center justify-between pt-2 border-t">
      <button onclick="prevTourStep()" class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg">← Back</button>
      <button id="tourPauseBtn" onclick="toggleTourPause()" class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-lg">⏸ Pause</button>
      <button onclick="nextTourStep()" class="px-4 py-1 bg-teal-600 text-white text-xs font-semibold rounded-lg">Next →</button>
    </div>
  </div>

  <script>
    const tourSteps = [
      { elementId: 'doc-dash', title: '🩺 ডক্টর প্যানেল', desc: 'এটি আপনার ডক্টর ড্যাশবোর্ড হোম পেজ।' },
      { elementId: 'doc-appts', title: '📋 রোগীদের অ্যাপয়েন্টমেন্ট', desc: 'আজকের বুকিং করা রোগীদের সিরিয়াল ও তালিকা এখান থেকে দেখতে পাবেন।' },
      { elementId: 'doc-presc', title: '✍️ প্রেসক্রিপশন লিখুন', desc: 'রোগী দেখার পর ডিজিটাল প্রেসক্রিপশন তৈরি করে সাবমিট করতে পারবেন।' },
      { elementId: 'doc-profile', title: '⚙️ চেম্বার ও শিডিউল', desc: 'আপনার ফি, ডিগ্রি এবং রোগী দেখার সময়সূচি আপডেট করুন।' }
    ];

    let currentTourIdx = 0;
    let tourInterval = null;
    let isPaused = false;

    function showTourStep(idx) {
      tourSteps.forEach(s => {
        const el = document.getElementById(s.elementId);
        if (el) el.classList.remove('tour-highlight');
      });
      const current = tourSteps[idx];
      document.getElementById('tourStepIndicator').textContent = `Step ${idx + 1} of ${tourSteps.length}`;
      document.getElementById('tourTitle').textContent = current.title;
      document.getElementById('tourDesc').textContent = current.desc;

      const targetEl = document.getElementById(current.elementId);
      if (targetEl) {
        targetEl.classList.add('tour-highlight');
        targetEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    }

    function nextTourStep() {
      currentTourIdx = (currentTourIdx + 1) % tourSteps.length;
      showTourStep(currentTourIdx);
    }

    function prevTourStep() {
      currentTourIdx = (currentTourIdx - 1 + tourSteps.length) % tourSteps.length;
      showTourStep(currentTourIdx);
    }

    function toggleTourPause() {
      isPaused = !isPaused;
      const btn = document.getElementById('tourPauseBtn');
      if (isPaused) {
        clearInterval(tourInterval);
        btn.textContent = '▶ Resume';
      } else {
        tourInterval = setInterval(nextTourStep, 4000);
        btn.textContent = '⏸ Pause';
      }
    }

    function closeLiveTour() {
      clearInterval(tourInterval);
      tourSteps.forEach(s => {
        const el = document.getElementById(s.elementId);
        if (el) el.classList.remove('tour-highlight');
      });
      document.getElementById('liveTourModal').style.display = 'none';
    }

    showTourStep(0);
    tourInterval = setInterval(nextTourStep, 4000);
  </script>
</body>
</html>