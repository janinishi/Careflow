<?php
/**
 * CareFlow - Patient Dashboard with In-Page Auto Tour Guide
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$display_name = $_SESSION['full_name'] ?? $_SESSION['name'] ?? 'Patient';

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT full_name, name FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) {
            $display_name = $user['full_name'] ?? $user['name'] ?? $display_name;
        }
    } catch (Exception $e) {}
}

$first_letter = strtoupper(mb_substr($display_name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title data-i18n="dash.title">Patient Dashboard - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Hind Siliguri', system-ui, -apple-system, sans-serif; }
    /* মেনু হাইলাইট করার জন্য পালস ইফেক্ট */
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
      <a href="patient-dashboard.php" id="menu-dash" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-teal-50 text-teal-700 font-semibold transition" data-i18n="side.dash">🏠 Dashboard</a>
      <a href="booking.html" id="menu-book" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.book">📅 Book Appointment</a>
      <a href="appointments.php" id="menu-appts" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.appts">🗓️ My Appointments</a>
      <a href="prescription.php" id="menu-presc" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.presc">💊 Prescriptions</a>
      <a href="blood-donors.php" id="menu-blood" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.blood">🩸 Blood Donors</a>
      <a href="report-doctor.php" id="menu-report" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.report">🛡️ Report Doctor</a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition" data-i18n="side.logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Area -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-4 flex items-center justify-between sticky top-0 z-10">
      <div class="flex items-center gap-3">
        <button id="menu-btn" type="button" class="md:hidden text-2xl text-gray-700 p-1">☰</button>
        <h1 class="text-xl font-bold text-gray-800" data-i18n="dash.h1">Patient Dashboard</h1>
      </div>
      <div class="flex items-center gap-3">
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 text-sm font-medium border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition">
          বাংলা
        </button>
        <span class="hidden sm:block text-sm text-gray-600 font-medium"><span data-i18n="hello.txt">Hello</span>, <strong class="text-gray-900"><?= htmlspecialchars($display_name) ?></strong></span>
        <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold">
          <?= htmlspecialchars($first_letter) ?>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 md:p-8 space-y-8 flex-1">

      <!-- Welcome Banner -->
      <div>
        <h2 class="text-2xl font-bold text-gray-900"><span data-i18n="dash.welcomePrefix">Welcome back</span>, <?= htmlspecialchars($display_name) ?> 👋</h2>
        <p class="text-gray-600 text-sm mt-1" data-i18n="dash.sub">Here is a quick look at your health activity.</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase" data-i18n="c1.l">Upcoming</p>
          <p class="text-3xl font-bold text-teal-600 mt-2">2</p>
          <p class="text-xs text-gray-500 mt-1" data-i18n="c1.v">Appointments</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase" data-i18n="c2.l">Completed</p>
          <p class="text-3xl font-bold text-green-600 mt-2">8</p>
          <p class="text-xs text-gray-500 mt-1" data-i18n="c2.v">Visits</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase" data-i18n="c3.l">Prescriptions</p>
          <p class="text-3xl font-bold text-blue-600 mt-2">6</p>
          <p class="text-xs text-gray-500 mt-1" data-i18n="c3.v">Total</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase" data-i18n="c4.l">Blood Group</p>
          <p class="text-3xl font-bold text-red-600 mt-2">O+</p>
          <p class="text-xs text-gray-500 mt-1" data-i18n="c4.v">Registered</p>
        </div>
      </div>

      <!-- Next Appointment Banner -->
      <div class="bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <span class="inline-block px-3 py-1 bg-teal-800 text-teal-100 text-xs font-semibold rounded-full mb-2" data-i18n="dash.next">Next Appointment</span>
          <p class="text-xl font-bold" data-i18n="dash.nextDoc">Dr. Anika Rahman (Cardiology)</p>
          <p class="text-teal-100 text-sm mt-1" data-i18n="dash.nextDate">25 Oct 2026, 10:30 AM</p>
        </div>
        <a href="booking.html" class="text-center px-5 py-2.5 bg-white text-teal-700 font-semibold rounded-lg hover:bg-teal-50 transition text-sm" data-i18n="dash.bookAnother">
          Book Another
        </a>
      </div>

      <!-- Medical History Table -->
      <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-bold text-lg text-gray-800" data-i18n="mh.h">Medical History</h3>
          <a href="prescription.php" class="text-sm font-medium text-teal-600 hover:underline" data-i18n="mh.viewAll">View all</a>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
              <tr>
                <th class="px-6 py-3" data-i18n="th.date">Date</th>
                <th class="px-6 py-3" data-i18n="th.doctor">Doctor</th>
                <th class="px-6 py-3" data-i18n="th.diagnosis">Diagnosis</th>
                <th class="px-6 py-3" data-i18n="th.meds">Medicines</th>
                <th class="px-6 py-3" data-i18n="th.status">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">12 Oct 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold" data-i18n="doc.anika">Dr. Anika Rahman</td>
                <td class="px-6 py-4 text-gray-600" data-i18n="diag.hypertension">Hypertension</td>
                <td class="px-6 py-4 text-gray-600" data-i18n="med.amlodipine">Amlodipine 5mg</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700" data-i18n="status.completed">Completed</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">28 Sep 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold" data-i18n="doc.karimH">Dr. Karim Hossain</td>
                <td class="px-6 py-4 text-gray-600" data-i18n="diag.flu">Seasonal Flu</td>
                <td class="px-6 py-4 text-gray-600" data-i18n="med.paracetamol">Paracetamol 500mg</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700" data-i18n="status.completed">Completed</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">25 Oct 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold" data-i18n="doc.anika">Dr. Anika Rahman</td>
                <td class="px-6 py-4 text-gray-600" data-i18n="diag.routine">Routine Checkup & Followup</td>
                <td class="px-6 py-4 text-gray-400" data-i18n="status.pendingApproval">Pending Approval</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700" data-i18n="status.pending">Pending</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

    </main>
  </div>

  <!-- 💡 অটো-পপআপ ট্যুর গাইড বক্স (ড্যাশবোর্ডের ডিজাইনের ওপর ভাসমান থাকবে) -->
  <div id="liveTourModal" class="fixed bottom-6 right-6 z-50 bg-white rounded-2xl shadow-2xl border border-teal-200 max-w-sm w-full p-5 space-y-3 transition-all duration-300">
    <div class="flex items-center justify-between border-b pb-2">
      <span id="tourStepIndicator" class="text-xs font-bold px-2.5 py-0.5 bg-teal-50 text-teal-700 rounded-full">Step 1 of 5</span>
      <button onclick="closeLiveTour()" class="text-xs text-gray-400 hover:text-gray-600 font-medium">Skip Tour ✕</button>
    </div>
    
    <div class="space-y-1.5">
      <h4 id="tourTitle" class="font-bold text-gray-800 text-base">Dashboard Overview</h4>
      <p id="tourDesc" class="text-xs text-gray-600 leading-relaxed"></p>
    </div>

    <div class="flex items-center justify-between pt-2 border-t">
      <button onclick="prevTourStep()" class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-200">← Back</button>
      <button id="tourPauseBtn" onclick="toggleTourPause()" class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-lg hover:bg-teal-100">⏸ Pause</button>
      <button onclick="nextTourStep()" class="px-4 py-1 bg-teal-600 text-white text-xs font-semibold rounded-lg hover:bg-teal-700">Next →</button>
    </div>
  </div>

  <script src="i18n.js"></script>

  <script>
    // Sidebar Toggle
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    if (menuBtn && sidebar) {
      menuBtn.addEventListener('click', function () {
        sidebar.classList.toggle('hidden');
      });
    }

    // লাইভ ড্যাশবোর্ড ট্যুর স্টেপস এবং মেনু আইডি ম্যাপিং
    const tourSteps = [
      {
        elementId: 'menu-dash',
        title: '🏠 ড্যাশবোর্ড ওভারভিউ',
        desc: 'এটি আপনার হোম পেজ। এখান থেকে আপনার সামগ্রিক হেলথ অ্যাক্টিভিটি ও পরিসংখ্যান দেখতে পাবেন।'
      },
      {
        elementId: 'menu-book',
        title: '📅 বুক অ্যাপয়েন্টমেন্ট',
        desc: 'এই অপশনে ক্লিক করে বিশেষজ্ঞ ডাক্তারদের তালিকা দেখে নতুন সিরিয়াল বুক করতে পারবেন।'
      },
      {
        elementId: 'menu-appts',
        title: '🗓️ আমার অ্যাপয়েন্টমেন্টসমূহ',
        desc: 'আপনার আপকামিং ও পূর্ববর্তী সমস্ত ভিজিট এবং সেগুলোর স্ট্যাটাস এখান থেকে চেক করতে পারবেন।'
      },
      {
        elementId: 'menu-presc',
        title: '💊 ডিজিটাল প্রেসক্রিপশন',
        desc: 'ডাক্তারদের দেওয়া সমস্ত প্রেসক্রিপশন এখানে সুরক্ষিত থাকে, যা যেকোনো সময় দেখতে বা ডাউনলোড করতে পারবেন।'
      },
      {
        elementId: 'menu-blood',
        title: '🩸 জরুরি ব্লাড ডোনারস',
        desc: 'জরুরি প্রয়োজনে রক্তের গ্রুপ অনুযায়ী ডোনারদের তালিকা খুঁজে সরাসরি যোগাযোগ করতে পারবেন।'
      }
    ];

    let currentTourIdx = 0;
    let tourInterval = null;
    let isPaused = false;

    function showTourStep(idx) {
      // আগের হাইলাইট রিমোভ করা
      tourSteps.forEach(step => {
        const el = document.getElementById(step.elementId);
        if (el) el.classList.remove('tour-highlight');
      });

      const current = tourSteps[idx];
      document.getElementById('tourStepIndicator').textContent = `Step ${idx + 1} of ${tourSteps.length}`;
      document.getElementById('tourTitle').textContent = current.title;
      document.getElementById('tourDesc').textContent = current.desc;

      // বর্তমান মেনু আইটেমটি হাইলাইট করা
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
      tourSteps.forEach(step => {
        const el = document.getElementById(step.elementId);
        if (el) el.classList.remove('tour-highlight');
      });
      document.getElementById('liveTourModal').style.display = 'none';
    }

    // অটো-প্লে শুরু করা (প্রতি ৪ সেকেন্ডে এক ধাপ এগোবে)
    showTourStep(0);
    tourInterval = setInterval(nextTourStep, 4000);
  </script>
</body>
</html>