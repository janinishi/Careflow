<?php
/**
 * CareFlow - Interactive Role-Based Onboarding Guide (Fixed & Stable)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$display_name = $_SESSION['full_name'] ?? 'User';
$role = $_SESSION['role'] ?? 'patient';

$target_dashboard = 'patient-dashboard.php';
if ($role === 'admin') {
    $target_dashboard = 'admin-dashboard.php';
} elseif ($role === 'doctor') {
    $target_dashboard = 'doctor-dashboard.php';
}

// সেফ এবং ক্লিয়ার স্টেপ ডেটা
$steps = [];
if ($role === 'doctor') {
    $steps = [
        ['icon' => '🩺', 'badge' => 'ধাপ ১ / ৪', 'title' => "স্বাগতম, ডা. {$display_name}!", 'desc' => 'CareFlow ডক্টর প্যানেলে আপনাকে স্বাগতম। আপনার মূল কাজগুলো এক নজরে দেখে নিন।', 'highlight' => 'Doctor Dashboard'],
        ['icon' => '📋', 'badge' => 'ধাপ ২ / ৪', 'title' => 'অ্যাপয়েন্টমেন্ট ম্যানেজমেন্ট', 'desc' => '"Appointments" বাটনে ক্লিক করে আজকের রোগীদের সিরিয়াল দেখতে ও কনফার্ম করতে পারবেন।', 'highlight' => 'Appointments Button'],
        ['icon' => '✍️', 'badge' => 'ধাপ ৩ / ৪', 'title' => 'ডিজিটাল প্রেসক্রিপশন', 'desc' => '"Write Prescription" বাটনের মাধ্যমে রোগীদের ওষুধ ও পরামর্শ ডিজিটালভাবে প্রদান করতে পারবেন।', 'highlight' => 'Write Prescription'],
        ['icon' => '⚙️', 'badge' => 'ধাপ ৪ / ৪', 'title' => 'চেম্বার ও শিডিউল', 'desc' => '"Profile Settings" থেকে আপনার ফি, ডিগ্রি এবং রোগী দেখার সময়সূচি আপডেট রাখতে পারবেন।', 'highlight' => 'Profile Settings']
    ];
} elseif ($role === 'admin') {
    $steps = [
        ['icon' => '👑', 'badge' => 'ধাপ ১ / ৪', 'title' => "স্বাগতম, {$display_name} (Admin)!", 'desc' => 'সম্পূর্ণ CareFlow সিস্টেম পরিচালনা করার কন্ট্রোল প্যানেল এটি।', 'highlight' => 'Admin Control Center'],
        ['icon' => '👥', 'badge' => 'ধাপ ২ / ৪', 'title' => 'ইউজার ও ডাক্তার ভেরিফিকেশন', 'desc' => '"Manage Users / Doctors" থেকে নতুন ডাক্তারদের অ্যাকাউন্ট অনুমোদন দিতে পারবেন।', 'highlight' => 'Manage Doctors'],
        ['icon' => '🚨', 'badge' => 'ধাপ ৩ / ৪', 'title' => 'ভুয়া ডাক্তার রিপোর্ট', 'desc' => '"Fake Doctor Reports" সেকশনে রোগীদের অভিযোগ যাচাই করে ব্যবস্থা নিতে পারবেন।', 'highlight' => 'Reports Section'],
        ['icon' => '📊', 'badge' => 'ধাপ ৪ / ৪', 'title' => 'সিস্টেম ওভারভিউ', 'desc' => 'ড্যাশবোর্ডের কার্ডগুলো থেকে মোট রোগী, ডাক্তার ও ডোনারদের পরিসংখ্যান দেখতে পাবেন।', 'highlight' => 'Dashboard Overview']
    ];
} else {
    // Default: Patient
    $steps = [
        ['icon' => '👋', 'badge' => 'ধাপ ১ / ৫', 'title' => "স্বাগতম, {$display_name}!", 'desc' => 'CareFlow সিস্টেমে আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে। চলুন বাটনগুলোর কাজ দেখে নিই।', 'highlight' => 'Patient Portal'],
        ['icon' => '📅', 'badge' => 'ধাপ ২ / ৫', 'title' => 'ডক্টর অ্যাপয়েন্টমেন্ট বুকিং', 'desc' => '"Book Appointment" বাটনে ক্লিক করে বিশেষজ্ঞ ডাক্তারদের তালিকা ও সিরিয়াল বুক করতে পারবেন।', 'highlight' => 'Book Appointment'],
        ['icon' => '💊', 'badge' => 'ধাপ ৩ / ৫', 'title' => 'প্রেসক্রিপশন ও রিপোর্ট', 'desc' => '"My Prescriptions" সেকশনে ডাক্তারদের দেওয়া সব প্রেসক্রিপশন সুরক্ষিত থাকবে।', 'highlight' => 'My Prescriptions'],
        ['icon' => '🩸', 'badge' => 'ধাপ ৪ / ৫', 'title' => 'জরুরি রক্তদাতা খুঁজুন', 'desc' => '"Blood Donors" বাটনে ক্লিক করে রক্তের গ্রুপ অনুযায়ী ডোনারের তালিকা ও ফোন নম্বর পাবেন।', 'highlight' => 'Blood Donors'],
        ['icon' => '🛡️', 'badge' => 'ধাপ ৫ / ৫', 'title' => 'সাপোর্ট ও নিরাপত্তা', 'desc' => 'কোনো সমস্যা হলে "Report / Support" থেকে সরাসরি অ্যাডমিনকে জানাতে পারবেন।', 'highlight' => 'Support Section']
    ];
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CareFlow - Welcome Guide</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Hind Siliguri', system-ui, sans-serif; }
    .fade-slide { animation: fadeSlide 0.3s ease-out; }
    @keyframes fadeSlide {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-slate-900/80 flex items-center justify-center min-h-screen p-4">

  <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100">
    <div class="w-full bg-gray-100 h-2">
      <div id="progressBar" class="bg-teal-600 h-2 transition-all duration-300" style="width: 20%;"></div>
    </div>

    <div class="p-8 space-y-6">
      <div class="flex items-center justify-between">
        <span id="stepBadge" class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full border border-teal-200">
          ধাপ ১
        </span>
        <a href="<?= htmlspecialchars($target_dashboard) ?>" class="text-xs text-gray-400 hover:text-gray-600 font-medium transition">
          স্কিপ করুন (Skip) ✕
        </a>
      </div>

      <div id="stepContent" class="text-center space-y-4 py-2 fade-slide">
        <div id="stepIcon" class="w-16 h-16 mx-auto bg-teal-50 rounded-2xl flex items-center justify-center text-4xl shadow-inner">
          👋
        </div>
        
        <h2 id="stepTitle" class="text-2xl font-bold text-gray-800">স্বাগতম!</h2>
        
        <p id="stepDesc" class="text-sm text-gray-600 leading-relaxed">লোড হচ্ছে...</p>

        <div>
          <span id="stepHighlight" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg border border-gray-200">
            📍 লোকেশন
          </span>
        </div>
      </div>

      <div id="stepDots" class="flex justify-center gap-2 pt-2"></div>

      <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100">
        <button id="prevBtn" onclick="changeStep(-1)" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition hidden">
          ← পূর্ববর্তী
        </button>
        
        <button id="nextBtn" onclick="changeStep(1)" class="ml-auto px-6 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 transition shadow-sm">
          পরবর্তী বাটন দেখুন ➔
        </button>

        <a id="finishBtn" href="<?= htmlspecialchars($target_dashboard) ?>" class="ml-auto px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-sm hidden items-center gap-2">
          ড্যাশবোর্ডে প্রবেশ করুন ✓
        </a>
      </div>
    </div>
  </div>

  <script>
    // PHP থেকে নিরাপদে ডেটা জাভাস্ক্রিপ্টে পাস করা হলো
    const steps = <?php echo json_encode($steps, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?>;
    let currentStep = 0;

    const progressBar = document.getElementById('progressBar');
    const stepBadge = document.getElementById('stepBadge');
    const stepContent = document.getElementById('stepContent');
    const stepIcon = document.getElementById('stepIcon');
    const stepTitle = document.getElementById('stepTitle');
    const stepDesc = document.getElementById('stepDesc');
    const stepHighlight = document.getElementById('stepHighlight');
    const stepDots = document.getElementById('stepDots');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const finishBtn = document.getElementById('finishBtn');

    function renderStep() {
      if (!steps || steps.length === 0) return;
      const data = steps[currentStep];
      
      stepContent.classList.remove('fade-slide');
      void stepContent.offsetWidth;
      stepContent.classList.add('fade-slide');

      stepBadge.textContent = data.badge;
      stepIcon.textContent = data.icon;
      stepTitle.textContent = data.title;
      stepDesc.textContent = data.desc;
      stepHighlight.textContent = '🔘 বাটন / মেনু: ' + data.highlight;

      const progressPercent = ((currentStep + 1) / steps.length) * 100;
      progressBar.style.width = progressPercent + '%';

      stepDots.innerHTML = '';
      steps.forEach((_, idx) => {
        const dot = document.createElement('span');
        dot.className = idx === currentStep 
          ? 'w-6 h-2 rounded-full bg-teal-600 transition-all duration-300' 
          : 'w-2 h-2 rounded-full bg-gray-200 transition-all duration-300';
        stepDots.appendChild(dot);
      });

      prevBtn.classList.toggle('hidden', currentStep === 0);

      if (currentStep === steps.length - 1) {
        nextBtn.classList.add('hidden');
        finishBtn.classList.remove('hidden');
        finishBtn.classList.add('inline-flex');
      } else {
        nextBtn.classList.remove('hidden');
        finishBtn.classList.add('hidden');
        finishBtn.classList.remove('inline-flex');
      }
    }

    function changeStep(direction) {
      currentStep += direction;
      if (currentStep < 0) currentStep = 0;
      if (currentStep >= steps.length) currentStep = steps.length - 1;
      renderStep();
    }

    // পেজ লোড হওয়ার সাথে সাথে রেন্ডার করবে
    window.onload = function() {
      renderStep();
    };
  </script>
</body>
</html>



<!-- ড্যাশবোর্ডের ভেতরে অটো-প্লেয়িং গাইড পপ-আপ -->
  <div id="dashboardTourModal" class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs z-50 flex items-center justify-center p-4">
    <div class="bg-white max-w-lg w-full rounded-3xl shadow-2xl p-6 md:p-8 space-y-5 border border-gray-100">
      
      <!-- Modal Header -->
      <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <span id="tourBadge" class="text-xs font-bold px-3 py-1 bg-teal-50 text-teal-700 rounded-full">Step 1</span>
        <button onclick="closeDashboardTour()" class="text-xs text-gray-400 hover:text-gray-600 font-medium">
          Skip Tour / স্কিপ ✕
        </button>
      </div>

      <!-- Tour Content -->
      <div class="text-center space-y-3 py-2">
        <div id="tourIcon" class="w-14 h-14 mx-auto bg-teal-50 rounded-2xl flex items-center justify-center text-3xl shadow-inner">
          📅
        </div>
        
        <div>
          <span id="tourBtnTag" class="inline-block px-4 py-1.5 bg-teal-600 text-white text-xs font-semibold rounded-lg shadow-sm">
            Button Name
          </span>
        </div>

        <h3 id="tourTitle" class="text-lg font-bold text-gray-800">Title</h3>
        <p id="tourDescEn" class="text-xs text-gray-600 leading-relaxed"></p>
        <p id="tourDescBn" class="text-xs text-teal-900 bg-teal-50/50 p-3 rounded-xl border border-teal-100 leading-relaxed"></p>
      </div>

      <!-- Dots Indicator -->
      <div id="tourDots" class="flex justify-center gap-2 pt-1"></div>

      <!-- Footer Controls -->
      <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <button id="tourPauseBtn" onclick="toggleTourPause()" class="px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition">
          ⏸ Pause
        </button>
        <button onclick="closeDashboardTour()" class="px-5 py-2 bg-teal-600 text-white text-xs font-semibold rounded-xl hover:bg-teal-700 shadow transition">
          Got it / বুঝেছি ✓
        </button>
      </div>
    </div>
  </div>

  <script>
    // ড্যাশবোর্ডের বাটনগুলোর অটো-ট্যুর ডেটা
    const tourSteps = [
      {
        icon: '📅',
        btn: 'Book Appointment',
        title: 'ডক্টর অ্যাপয়েন্টমেন্ট বুকিং',
        en: 'Click this to search specialist doctors by department and book your visit slot online.',
        bn: 'এই বাটনে ক্লিক করে বিভাগ অনুযায়ী বিশেষজ্ঞ ডাক্তার খুঁজে অনলাইনে সিরিয়াল বুক করতে পারবেন।'
      },
      {
        icon: '🗓️',
        btn: 'My Appointments',
        title: 'আপনার অ্যাপয়েন্টমেন্টসমূহ',
        en: 'View all your upcoming and past medical visits and track approval statuses here.',
        bn: 'আপনার সমস্ত আপকামিং ও পূর্ববর্তী ভিজিট এবং সেগুলোর অনুমোদন স্ট্যাটাস এখানে দেখতে পাবেন।'
      },
      {
        icon: '💊',
        btn: 'Prescriptions',
        title: 'ডিজিটাল প্রেসক্রিপশন ও রিপোর্ট',
        en: 'Access all your doctor-issued medical prescriptions and download them anytime.',
        bn: 'ডাক্তারদের দেওয়া সব ডিজিটাল প্রেসক্রিপশন সুরক্ষিত থাকবে এবং যেকোনো সময় ডাউনলোড করতে পারবেন।'
      },
      {
        icon: '🩸',
        btn: 'Blood Donors',
        title: 'জরুরি রক্তদাতা খুঁজুন',
        en: 'Search available blood donors by blood group during medical emergencies.',
        bn: 'জরুরি প্রয়োজনে রক্তের গ্রুপ অনুযায়ী রক্তদাতাদের তালিকা খুঁজে সরাসরি যোগাযোগ করতে পারবেন।'
      },
      {
        icon: '🛡️',
        btn: 'Report Doctor',
        title: 'অভিযোগ বা সাপোর্ট',
        en: 'Encountered any issue or suspicious activity? Submit a report directly to the admin.',
        bn: 'কোনো সমস্যা বা সন্দেহজনক কিছু দেখলে সরাসরি অ্যাডমিনকে রিপোর্ট বা সাপোর্ট রিকুয়েস্ট পাঠাতে পারবেন।'
      }
    ];

    let tourIndex = 0;
    let tourTimer = null;
    let isTourPaused = false;

    function renderTourStep() {
      const step = tourSteps[tourIndex];
      document.getElementById('tourBadge').textContent = `Step ${tourIndex + 1} of ${tourSteps.length}`;
      document.getElementById('tourIcon').textContent = step.icon;
      document.getElementById('tourBtnTag').textContent = '🔘 ' + step.btn;
      document.getElementById('tourTitle').textContent = step.title;
      document.getElementById('tourDescEn').textContent = step.en;
      document.getElementById('tourDescBn').textContent = step.bn;

      // Dots update
      const dotsContainer = document.getElementById('tourDots');
      dotsContainer.innerHTML = '';
      tourSteps.forEach((_, idx) => {
        const dot = document.createElement('span');
        dot.className = idx === tourIndex 
          ? 'w-6 h-1.5 rounded-full bg-teal-600 transition-all duration-300' 
          : 'w-1.5 h-1.5 rounded-full bg-gray-200 transition-all duration-300';
        dotsContainer.appendChild(dot);
      });
    }

    function nextTourStep() {
      if (isTourPaused) return;
      tourIndex = (tourIndex + 1) % tourSteps.length;
      renderTourStep();
    }

    function toggleTourPause() {
      isTourPaused = !isTourPaused;
      const btn = document.getElementById('tourPauseBtn');
      btn.textContent = isTourPaused ? '▶ Resume' : '⏸ Pause';
    }

    function closeDashboardTour() {
      clearInterval(tourTimer);
      document.getElementById('dashboardTourModal').style.display = 'none';
    }

    // প্রথমবার লোড হওয়ার সাথে সাথে শুরু হবে এবং প্রতি ৩.৫ সেকেন্ড পর পর বদলাবে
    renderTourStep();
    tourTimer = setInterval(nextTourStep, 3500);
  </script>