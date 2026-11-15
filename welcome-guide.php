<?php
/**
 * CareFlow - Auto-Playing Interactive Button Tour (Bilingual)
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

$dashboards = [
    'patient' => 'patient-dashboard.php',
    'doctor'  => 'doctor-dashboard.php',
    'admin'   => 'admin-dashboard.php',
    'donor'   => 'donor-dashboard.php'
];
$target_dashboard = $dashboards[$role] ?? 'patient-dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CareFlow - Interactive Tour</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', 'Hind Siliguri', sans-serif; }
    .fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(4px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-slate-900/80 flex items-center justify-center min-h-screen p-4">

  <div class="bg-white max-w-3xl w-full rounded-3xl shadow-2xl p-6 md:p-8 space-y-6 border border-gray-100">
    
    <!-- Header -->
    <div class="flex items-center justify-between border-b pb-4">
      <div>
        <h1 class="text-xl md:text-2xl font-bold text-teal-700">Welcome, <?= htmlspecialchars($display_name) ?>! (<?= ucfirst($role) ?>)</h1>
        <p class="text-xs md:text-sm text-gray-500">এখানে আপনার ড্যাশবোর্ডের বাটনগুলোর কাজ স্বয়ংক্রিয়ভাবে দেখানো হচ্ছে / Auto Tour</p>
      </div>
      <a href="<?= htmlspecialchars($target_dashboard) ?>" class="text-xs text-gray-400 hover:text-gray-600 font-medium">
        Skip Tour / স্কিপ ✕
      </a>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
      
      <!-- Left Side: Buttons List -->
      <div class="space-y-2.5" id="btnList"></div>

      <!-- Right Side: Dynamic Details Box -->
      <div class="bg-teal-50/70 border border-teal-100 rounded-2xl p-6 space-y-3 min-h-[220px] flex flex-col justify-center fade-in" id="detailBox">
        <span id="detailBadge" class="text-xs font-bold uppercase tracking-wider text-teal-600">Auto Preview ➔</span>
        <h3 id="detailTitle" class="text-lg font-bold text-gray-800"></h3>
        <p id="detailEn" class="text-sm text-gray-700"></p>
        <p id="detailBn" class="text-sm text-teal-900 bg-white p-3 rounded-xl border border-teal-100"></p>
      </div>
    </div>

    <!-- Footer Controls -->
    <div class="pt-4 border-t flex items-center justify-between">
      <button id="pausePlayBtn" onclick="toggleAutoPlay()" class="px-4 py-2 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition">
        ⏸ Pause Auto-Tour / পজ করুন
      </button>

      <a href="<?= htmlspecialchars($target_dashboard) ?>" class="px-6 py-2.5 bg-teal-600 text-white text-xs md:text-sm font-semibold rounded-xl hover:bg-teal-700 shadow transition flex items-center gap-2">
        Continue to Dashboard / ড্যাশবোর্ডে যান ➔
      </a>
    </div>

  </div>

  <script>
    const role = <?= json_encode($role) ?>;
    
    // রোল অনুযায়ী বাটন ও বিবরণী ডেটা
    const dataMap = {
      patient: [
        { label: '📅 Book Appointment', title: 'Doctor Appointment / অ্যাপয়েন্টমেন্ট', en: 'Click "Book Appointment" to search specialist doctors and book an online slot.', bn: 'বিশেষজ্ঞ ডাক্তার খুঁজে অনলাইনে সিরিয়াল বুক করতে পারবেন।' },
        { label: '💊 My Prescriptions', title: 'Digital Prescriptions / প্রেসক্রিপশন', en: 'Click "My Prescriptions" to view and download all doctor-issued prescriptions.', bn: 'ডাক্তারদের দেওয়া সব ডিজিটাল প্রেসক্রিপশন দেখতে ও ডাউনলোড করতে পারবেন।' },
        { label: '🩸 Blood Donors', title: 'Find Blood Donors / ব্লাড ডোনার', en: 'Click "Blood Donors" to find available blood donors by blood group during emergencies.', bn: 'জরুরি রক্তের প্রয়োজনে গ্রুপ অনুযায়ী রক্তদাতা খুঁজে যোগাযোগ করতে পারবেন।' },
        { label: '🛡️ Report Doctor', title: 'Safety & Report / অভিযোগ জানান', en: 'Click "Report Doctor" to report fake doctors or submit support feedback to admin.', bn: 'ভুয়া ডাক্তার বা যেকোনো সমস্যা সরাসরি অ্যাডমিনকে রিপোর্ট করতে পারবেন।' }
      ],
      doctor: [
        { label: '📋 Today’s Appointments', title: 'Patient Serials / রোগীদের তালিকা', en: 'Click to view scheduled patients, approve visits, and update status.', bn: 'আজকের রোগীদের সিরিয়াল দেখুন এবং কনসালটেশন স্ট্যাটাস আপডেট করুন।' },
        { label: '✍️ Write Prescription', title: 'E-Prescription / প্রেসক্রিপশন লিখুন', en: 'Use this to create digital prescriptions that patients receive instantly.', bn: 'রোগীদের জন্য ডিজিটাল প্রেসক্রিপশন লিখে সাবমিট করুন।' },
        { label: '⚙️ Chamber Settings', title: 'Schedule & Fees / চেম্বার সেটিংস', en: 'Update your visiting hours, chamber address, and consultation fees.', bn: 'আপনার রোগী দেখার সময়সূচি, চেম্বার ও ফি আপডেট করুন।' }
      ],
      admin: [
        { label: '👥 Manage Users', title: 'User & Doctor Control / ইউজার ম্যানেজমেন্ট', en: 'Approve new doctor registrations and manage user access permissions.', bn: 'নতুন ডাক্তারদের অনুমোদন দিন এবং সমস্ত ইউজার অ্যাকাউন্ট নিয়ন্ত্রণ করুন।' },
        { label: '🚨 Fake Doctor Reports', title: 'Review Complaints / রিপোর্ট যাচাই', en: 'Inspect patient complaints and take strict action against unverified profiles.', bn: 'রোগীদের পাঠানো অভিযোগ যাচাই করে ভুয়া প্রফাইলের বিরুদ্ধে ব্যবস্থা নিন।' },
        { label: '🩸 Blood Bank List', title: 'Donor Database / ব্লাড ব্যাংক', en: 'Monitor registered blood donors and availability statuses across the system.', bn: 'নিবন্ধিত ব্লাড ডোনারদের তালিকা ও লাইভ স্ট্যাটাস মনিটর করুন।' }
      ]
    };

    const items = dataMap[role] || dataMap.patient;
    const btnList = document.getElementById('btnList');
    let currentIndex = 0;
    let autoPlayTimer = null;
    let isPaused = false;

    function selectItem(idx, manual = false) {
      currentIndex = idx;
      const item = items[idx];

      // অ্যানিমেশন রিফ্রেশ করার জন্য
      const box = document.getElementById('detailBox');
      box.classList.remove('fade-in');
      void box.offsetWidth;
      box.classList.add('fade-in');

      // টেক্সট আপডেট
      document.getElementById('detailTitle').textContent = item.title;
      document.getElementById('detailEn').textContent = item.en;
      document.getElementById('detailBn').textContent = item.bn;

      // বাটনের স্টাইল আপডেট (সিলেক্টেড বাটনটি সবুজ দেখাবে)
      Array.from(btnList.children).forEach((el, i) => {
        if (i === idx) {
          el.className = 'w-full text-left px-4 py-3 rounded-xl font-semibold text-sm bg-teal-600 text-white shadow-md transition flex items-center justify-between';
          el.innerHTML = `<span>${item.label}</span> <span class="text-xs bg-white/20 px-2 py-0.5 rounded">Active ◄</span>`;
        } else {
          el.className = 'w-full text-left px-4 py-3 rounded-xl font-semibold text-sm bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-100 transition flex items-center justify-between';
          el.innerHTML = `<span>${items[i].label}</span>`;
        }
      });

      // ম্যানুয়ালি ক্লিক করলে অটো-প্লে সাময়িক বন্ধ রাখতে চাইলে রাখতে পারেন, না চাইলে চলতে থাকবে
    }

    function nextSlide() {
      if (isPaused) return;
      currentIndex = (currentIndex + 1) % items.length;
      selectItem(currentIndex);
    }

    function toggleAutoPlay() {
      isPaused = !isPaused;
      const btn = document.getElementById('pausePlayBtn');
      if (isPaused) {
        btn.textContent = '▶ Resume Auto-Tour / চালু করুন';
        btn.className = 'px-4 py-2 text-xs font-semibold bg-teal-50 text-teal-700 rounded-xl transition';
      } else {
        btn.textContent = '⏸ Pause Auto-Tour / পজ করুন';
        btn.className = 'px-4 py-2 text-xs font-semibold bg-gray-100 text-gray-700 rounded-xl transition';
      }
    }

    // বাটন লিস্ট তৈরি করা
    items.forEach((item, idx) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.onclick = () => {
        isPaused = true; // ম্যানুয়ালি ক্লিক করলে অটো-প্লে পজ হয়ে যাবে যাতে ইউজার সহজে দেখতে পারে
        document.getElementById('pausePlayBtn').textContent = '▶ Resume Auto-Tour / চালু করুন';
        document.getElementById('pausePlayBtn').className = 'px-4 py-2 text-xs font-semibold bg-teal-50 text-teal-700 rounded-xl transition';
        selectItem(idx, true);
      };
      btnList.appendChild(btn);
    });

    // প্রথম আইটেম সিলেক্ট করা
    selectItem(0);

    // প্রতি ৩.৫ সেকেন্ড পর পর নিজে থেকেই পরের বাটনে চলে যাবে (Auto Tour)
    autoPlayTimer = setInterval(nextSlide, 3500);
  </script>
</body>
</html>