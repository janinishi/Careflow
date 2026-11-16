<?php
/**
 * CareFlow - Dynamic Patient Dashboard with 1-Time Welcome Tour
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

// যদি লগইন করা না থাকে, তবে লগইন পেজে রিডাইরেক্ট করবে
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// সেশন বা ডাটাবেস থেকে ইউজারের আসল নাম নিশ্চিত করা
$display_name = $_SESSION['full_name'] ?? $_SESSION['name'] ?? 'Patient';

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT full_name, name FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) {
            $display_name = $user['full_name'] ?? $user['name'] ?? $display_name;
        }
    } catch (Exception $e) {
        // Fallback to session name
    }
}

// নামের প্রথম অক্ষর (Avatar-এর জন্য)
$first_letter = strtoupper(mb_substr($display_name, 0, 1));
$current_user_id = $_SESSION['user_id'] ?? 'guest';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>রোগীর ড্যাশবোর্ড - কেয়ারফ্লো</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Hind Siliguri', system-ui, -apple-system, sans-serif; }
    /* মেনু হাইলাইট স্টাইল */
    .tour-target-highlight {
      background-color: #ffffff !important;
      border: 2px solid #0d9488 !important;
      box-shadow: 0 0 15px rgba(13, 148, 136, 0.3) !important;
      border-radius: 12px;
      position: relative;
      z-index: 60;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col md:flex-row">

  <!-- Sidebar -->
  <aside id="sidebar" class="hidden md:block w-full md:w-64 bg-white border-r border-gray-200 shrink-0 min-h-screen">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <a href="index.html" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>
    </div>

    <nav class="p-4 space-y-1 text-sm font-medium">
      <a href="patient-dashboard.php" id="menu-dash" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 transition">🏠 ড্যাশবোর্ড</a>
      <a href="booking.html" id="menu-book" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">📅 অ্যাপয়েন্টমেন্ট বুকিং</a>
      <a href="appointments.php" id="menu-appts" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">🗓️ আমার অ্যাপয়েন্টমেন্ট</a>
      <a href="prescription.php" id="menu-presc" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">💊 প্রেসক্রিপশন</a>
      <a href="blood-donors.php" id="menu-blood" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">🩸 রক্তদাতা</a>
      <a href="report-doctor.php" id="menu-report" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">🛡️ রিপোর্ট ডাক্তার</a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition">🚪 লগআউট</a>
    </nav>
  </aside>

  <!-- Main Area -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-4 flex items-center justify-between sticky top-0 z-[70]">
      <div class="flex items-center gap-3">
        <button id="menu-btn" type="button" class="md:hidden text-2xl text-gray-700 p-1">☰</button>
        <h1 class="text-xl font-bold text-gray-800">রোগীর ড্যাশবোর্ড</h1>
      </div>
      <div class="flex items-center gap-3">
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 text-sm font-medium border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition cursor-pointer">
          English
        </button>
        <!-- ডাইনামিক ইউজারের নাম -->
        <span class="hidden sm:block text-sm text-gray-600 font-medium">হ্যালো, <strong class="text-gray-900"><?= htmlspecialchars($display_name) ?></strong></span>
        <!-- ডাইনামিক অবতারের প্রথম অক্ষর -->
        <div class="w-9 h-9 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold">
          <?= htmlspecialchars($first_letter) ?>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 md:p-8 space-y-8 flex-1">

      <!-- Welcome Banner -->
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Welcome back, <?= htmlspecialchars($display_name) ?> 👋</h2>
        <p class="text-gray-600 text-sm mt-1">আপনার স্বাস্থ্য কর্মকাণ্ডের একটি সংক্ষিপ্ত বিবরণ।</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase">আসন্ন</p>
          <p class="text-3xl font-bold text-teal-600 mt-2">2</p>
          <p class="text-xs text-gray-500 mt-1">অ্যাপয়েন্টমেন্ট</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase">সম্পন্ন</p>
          <p class="text-3xl font-bold text-green-600 mt-2">8</p>
          <p class="text-xs text-gray-500 mt-1">সাক্ষাৎ</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase">প্রেসক্রিপশন</p>
          <p class="text-3xl font-bold text-blue-600 mt-2">6</p>
          <p class="text-xs text-gray-500 mt-1">মোট</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
          <p class="text-xs font-semibold text-gray-500 uppercase">রক্তের গ্রুপ</p>
          <p class="text-3xl font-bold text-red-600 mt-2">O+</p>
          <p class="text-xs text-gray-500 mt-1">রেজিস্টার্ড</p>
        </div>
      </div>

      <!-- Next Appointment Banner -->
      <div class="bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-xl p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <span class="inline-block px-3 py-1 bg-teal-800 text-teal-100 text-xs font-semibold rounded-full mb-2">পরবর্তী অ্যাপয়েন্টমেন্ট</span>
          <p class="text-xl font-bold">ডা. আনিকা রহমান (হৃদরোগ বিশেষজ্ঞ)</p>
          <p class="text-teal-100 text-sm mt-1">২৫ অক্টোবর ২০২৬, সকাল ১০:৩০</p>
        </div>
        <a href="booking.html" class="text-center px-5 py-2.5 bg-white text-teal-700 font-semibold rounded-lg hover:bg-teal-50 transition text-sm">
          আরেকটি বুক করুন
        </a>
      </div>

      <!-- Medical History Table -->
      <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-bold text-lg text-gray-800">মেডিকেল হিস্ট্রি</h3>
          <a href="prescription.php" class="text-sm font-medium text-teal-600 hover:underline">সব দেখুন</a>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
              <tr>
                <th class="px-6 py-3">তারিখ</th>
                <th class="px-6 py-3">ডাক্তারের নাম</th>
                <th class="px-6 py-3">রোগ নির্ণয় (ডায়াগনোসিস)</th>
                <th class="px-6 py-3">ঔষধ</th>
                <th class="px-6 py-3">অবস্থা</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">12 Oct 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold">Dr. Anika Rahman</td>
                <td class="px-6 py-4 text-gray-600">Hypertension</td>
                <td class="px-6 py-4 text-gray-600">Amlodipine 5mg</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">সম্পন্ন</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">28 Sep 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold">Dr. Karim Hossain</td>
                <td class="px-6 py-4 text-gray-600">Seasonal Flu</td>
                <td class="px-6 py-4 text-gray-600">Paracetamol 500mg</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">সম্পন্ন</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">25 Oct 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold">Dr. Anika Rahman</td>
                <td class="px-6 py-4 text-gray-400">-</td>
                <td class="px-6 py-4 text-gray-400">-</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">পেন্ডিং</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

    </main>
  </div>

  <!-- 💡 ১. প্রথম ওয়েলকাম ওভারলে পপ-আপ (বাংলায় ফিক্সড) -->
  <div id="welcomeOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white max-w-xl w-full rounded-3xl shadow-2xl p-6 md:p-8 space-y-6 border border-gray-100 text-center relative">
      <div class="w-16 h-16 mx-auto bg-amber-50 rounded-2xl flex items-center justify-center text-3xl shadow-inner">🎉</div>
      <div>
        <h2 class="text-2xl font-bold text-gray-800">স্বাগতম, <?= htmlspecialchars($display_name) ?>!</h2>
        <p class="text-xs md:text-sm text-gray-500 mt-1">CareFlow-তে আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে। নিচে দেখে নিন কোথায় কোন ফিচারটি পাবেন:</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
        <div class="p-3.5 bg-teal-50/50 border border-teal-100 rounded-2xl space-y-1">
          <span class="text-xs font-bold text-teal-700">📅 বুক অ্যাপয়েন্টমেন্ট</span>
          <p class="text-[11px] text-gray-600">নতুন ডাক্তার ও ভিজিটের জন্য সময় বুক করতে পারবেন।</p>
        </div>
        <div class="p-3.5 bg-blue-50/50 border border-blue-100 rounded-2xl space-y-1">
          <span class="text-xs font-bold text-blue-700">💊 প্রেসক্রিপশন</span>
          <p class="text-[11px] text-gray-600">ডাক্তারদের দেওয়া সব প্রেসক্রিপশন এখানে পাবেন।</p>
        </div>
        <div class="p-3.5 bg-red-50/50 border border-red-100 rounded-2xl space-y-1">
          <span class="text-xs font-bold text-teal-700">🩸 রক্তদাতা</span>
          <p class="text-[11px] text-gray-600">জরুরি প্রয়োজনে রক্তের ডোনারদের তালিকা দেখতে পাবেন।</p>
        </div>
        <div class="p-3.5 bg-amber-50/50 border border-amber-100 rounded-2xl space-y-1">
          <span class="text-xs font-bold text-amber-700">🛡️ রিপোর্ট ডাক্তার</span>
          <p class="text-[11px] text-gray-600">কোনো অভিযোগ বা মতামত জানানোর জন্য।</p>
        </div>
      </div>

      <button onclick="startDashboardTour()" class="w-full py-3.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-teal-600/30 transition text-sm flex items-center justify-center gap-2 cursor-pointer">
        ড্যাশবোর্ড ব্যবহার শুরু করুন ➔
      </button>
    </div>
  </div>

  <!-- 💡 ২. ড্যাশবোর্ড ট্যুর পপ-আপ -->
  <div id="liveTourModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-teal-100 max-w-md w-full p-6 space-y-4 relative">
      <div class="flex items-center justify-between border-b pb-3">
        <span id="tourStepIndicator" class="text-xs font-bold px-3 py-1 bg-teal-50 text-teal-700 rounded-full">Step 1 of 6</span>
        <button onclick="closeLiveTour()" class="text-xs text-gray-400 hover:text-gray-600 font-medium cursor-pointer">Skip Tour ✕</button>
      </div>
      
      <div class="space-y-2 py-2">
        <h4 id="tourTitle" class="font-bold text-gray-800 text-lg"></h4>
        <p id="tourDesc" class="text-xs md:text-sm text-gray-600 leading-relaxed bg-gray-50 p-3.5 rounded-2xl border border-gray-100"></p>
      </div>

      <div class="flex items-center justify-between pt-3 border-t">
        <button onclick="prevTourStep()" id="tourPrevBtn" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-200 transition cursor-pointer">← Back</button>
        <button onclick="nextTourStep()" id="tourNextBtn" class="px-5 py-2 bg-teal-600 text-white text-xs font-semibold rounded-xl hover:bg-teal-700 transition shadow-sm cursor-pointer">Next →</button>
      </div>
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

    const tourKey = "careflow_tour_seen_<?php echo $current_user_id; ?>";

    document.addEventListener("DOMContentLoaded", function() {
      const hasSeenTour = localStorage.getItem(tourKey);
      if (!hasSeenTour) {
        const overlay = document.getElementById('welcomeOverlay');
        if (overlay) overlay.classList.remove('hidden');
      }
    });

    function markTourAsSeen() {
      localStorage.setItem(tourKey, "true");
    }

    function startDashboardTour() {
      const overlay = document.getElementById('welcomeOverlay');
      if (overlay) overlay.classList.add('hidden');
      document.getElementById('liveTourModal').classList.remove('hidden');
      showTourStep(0);
    }

    const tourSteps = [
      { elementId: 'menu-dash', titleKey: 'tour.step1.title', descKey: 'tour.step1.desc' },
      { elementId: 'menu-book', titleKey: 'tour.step2.title', descKey: 'tour.step2.desc' },
      { elementId: 'menu-appts', titleKey: 'tour.step3.title', descKey: 'tour.step3.desc' },
      { elementId: 'menu-presc', titleKey: 'tour.step4.title', descKey: 'tour.step4.desc' },
      { elementId: 'menu-blood', titleKey: 'tour.step5.title', descKey: 'tour.step5.desc' },
      { elementId: 'menu-report', titleKey: 'tour.step6.title', descKey: 'tour.step6.desc' }
    ];

    let currentTourIdx = 0;

    function showTourStep(idx) {
      tourSteps.forEach(step => {
        const el = document.getElementById(step.elementId);
        if (el) el.classList.remove('tour-target-highlight');
      });

      const current = tourSteps[idx];
      document.getElementById('tourStepIndicator').textContent = `Step ${idx + 1} of ${tourSteps.length}`;
      document.getElementById('tourTitle').textContent = t(current.titleKey);
      document.getElementById('tourDesc').textContent = t(current.descKey);

      const targetEl = document.getElementById(current.elementId);
      if (targetEl) {
        targetEl.classList.add('tour-target-highlight');
        targetEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    }

    function updateTourLanguage() {
      const modal = document.getElementById('liveTourModal');
      if (modal && !modal.classList.contains('hidden')) {
        showTourStep(currentTourIdx);
      }
    }

    function nextTourStep() {
      if (currentTourIdx < tourSteps.length - 1) {
        currentTourIdx++;
        showTourStep(currentTourIdx);
      } else {
        closeLiveTour();
      }
    }

    function prevTourStep() {
      if (currentTourIdx > 0) {
        currentTourIdx--;
        showTourStep(currentTourIdx);
      }
    }

    function closeLiveTour() {
      tourSteps.forEach(step => {
        const el = document.getElementById(step.elementId);
        if (el) el.classList.remove('tour-target-highlight');
      });
      document.getElementById('liveTourModal').classList.add('hidden');
      markTourAsSeen();
    }

    function toggleLang() {
      currentLang = currentLang === "en" ? "bn" : "en";
      localStorage.setItem("careflow_lang", currentLang);
      location.reload();
    }
  </script>

</body>
</html>