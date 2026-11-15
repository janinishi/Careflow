<?php
/**
 * CareFlow - Interactive Role-Based Onboarding Guide (Step-by-Step Tour)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// যদি ইউজার লগইন বা রেজিস্টার করা না থাকে, তবে লগইন পেজে পাঠাবে
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$display_name =$_SESSION['full_name'] ?? 'User';
$role =$_SESSION['role'] ?? 'patient';

// রোল অনুযায়ী সঠিক ড্যাশবোর্ডের লিংক নির্ধারণ
$target_dashboard = 'patient-dashboard.php';
if ($role === 'admin') {$target_dashboard = 'admin-dashboard.php';
} elseif ($role === 'doctor') {$target_dashboard = 'doctor-dashboard.php';
}

// প্রত্যেক ইউজারের রোল অনুযায়ী আলাদা আলাদা স্টেপস (Steps)
$role_steps = [
    'patient' => [
        [
            'icon' => '👋',
            'badge' => 'ধাপ ১ / ৫ • স্বাগতম',
            'title' => "স্বাগতম, {$display_name}!",
            'desc' => 'CareFlow ডিজিটাল হেলথকেয়ার সিস্টেমে আপনার অ্যাকাউন্ট তৈরি সফল হয়েছে। চলুন ১ মিনিটে দেখে নিই আপনার ড্যাশবোর্ডের কোন বাটনে কী কাজ রয়েছে।',
            'highlight' => 'Patient Portal Overview'
        ],
        [
            'icon' => '📅',
            'badge' => 'ধাপ ২ / ৫ • অ্যাপয়েন্টমেন্ট',
            'title' => 'ডক্টর অ্যাপয়েন্টমেন্ট বুকিং',
            'desc' => 'আপনার ড্যাশবোর্ডের "Book Appointment" বাটনে ক্লিক করে বিশেষজ্ঞ ডাক্তারদের তালিকা দেখতে পারবেন এবং পছন্দের সময়ে সিরিয়াল বুক করতে পারবেন।',
            'highlight' => 'ড্যাশবোর্ড ➔ Book Appointment'
        ],
        [
            'icon' => '💊',
            'badge' => 'ধাপ ৩ / ৫ • প্রেসক্রিপশন',
            'title' => 'ডিজিটাল প্রেসক্রিপশন ও রিপোর্ট',
            'desc' => '"My Prescriptions" সেকশনে ডাক্তারদের দেওয়া সব প্রেসক্রিপশন ও টেস্ট রিপোর্ট সুরক্ষিত থাকবে। কাগজ হারানোর ভয় ছাড়াই যেকোনো সময় ডাউনলোড করতে পারবেন।',
            'highlight' => 'মেনু ➔ My Prescriptions'
        ],
        [
            'icon' => '🩸',
            'badge' => 'ধাপ ৪ / ৫ • জরুরি রক্তদান',
            'title' => 'ব্লাড ডোনার খোঁজা ও যোগাযোগ',
            'desc' => 'জরুরি প্রয়োজনে "Blood Donors" বাটনে ক্লিক করে রক্তের গ্রুপ অনুযায়ী ডোনারদের তালিকা ও ফোন নম্বর পেয়ে যাবেন।',
            'highlight' => 'মেনু ➔ Blood Donors'
        ],
        [
            'icon' => '🛡️',
            'badge' => 'ধাপ ৫ / ৫ • সাপোর্ট ও নিরাপত্তা',
            'title' => 'ভুয়া ডাক্তার রিপোর্ট ও সাপোর্ট',
            'desc' => 'কোনো ডাক্তার বা সেবা নিয়ে অভিযোগ থাকলে "Report / Support" বাটনের মাধ্যমে সরাসরি অ্যাডমিনকে জানাতে পারবেন। এবার ড্যাশবোর্ডে প্রবেশ করুন!',
            'highlight' => 'সবকিছু প্রস্তুত!'
        ]
    ],
    'doctor' => [
        [
            'icon' => '🩺',
            'badge' => 'ধাপ ১ / ৪ • স্বাগতম ডাক্তার',
            'title' => "স্বাগতম, ডা. {$display_name}!",
            'desc' => 'CareFlow ডক্টর প্যানেলে আপনাকে স্বাগতম। আপনার রোগীদের সিরিয়াল ও প্রেসক্রিপশন ম্যানেজ করার ফিচারগুলো এক নজরে দেখে নিন।',
            'highlight' => 'Doctor Portal Overview'
        ],
        [
            'icon' => '📋',
            'badge' => 'ধাপ ২ / ৪ • অ্যাপয়েন্টমেন্ট তালিকা',
            'title' => 'রোগীদের অ্যাপয়েন্টমেন্ট ম্যানেজমেন্ট',
            'desc' => '"Appointments" বাটনে ক্লিক করলে আজকের ও আগামী দিনের বুকিং করা রোগীদের সিরিয়াল দেখতে এবং কনফার্ম বা সম্পন্ন করতে পারবেন।',
            'highlight' => 'ড্যাশবোর্ড ➔ Appointments'
        ],
        [
            'icon' => '✍️',
            'badge' => 'ধাপ ৩ / ৪ • ই-প্রেসক্রিপশন',
            'title' => 'ডিজিটাল প্রেসক্রিপশন তৈরি',
            'desc' => 'রোগী দেখার পর "Write Prescription" বাটনের মাধ্যমে ওষুধ ও পরামর্শ লিখে সাবমিট করলে রোগী তাৎক্ষণিক তার অ্যাকাউন্টে পেয়ে যাবেন।',
            'highlight' => 'রোগীর প্রোফাইল ➔ Write Prescription'
        ],
        [
            'icon' => '⚙️',
            'badge' => 'ধাপ ৪ / ৪ • চেম্বার ও শিডিউল',
            'title' => 'প্রোফাইল ও ভিজিটিং আওয়ার',
            'desc' => '"Profile Settings" থেকে আপনার ডিগ্রি, বিশেষজ্ঞতা, ফি এবং রোগী দেখার সময়সূচি আপডেট রাখতে পারবেন।',
            'highlight' => 'সবকিছু প্রস্তুত!'
        ]
    ],
    'admin' => [
        [
            'icon' => '👑',
            'badge' => 'ধাপ ১ / ৪ • অ্যাডমিন প্যানেল',
            'title' => "স্বাগতম, {$display_name} (Admin)!",
            'desc' => 'সম্পূর্ণ CareFlow সিস্টেম পরিচালনা করার জন্য আপনার কন্ট্রোল প্যানেল প্রস্তুত। প্রধান মডিউলগুলো দেখে নিন।',
            'highlight' => 'Admin Control Center'
        ],
        [
            'icon' => '👥',
            'badge' => 'ধাপ ২ / ৪ • ইউজার ও ডক্টর ম্যানেজমেন্ট',
            'title' => 'ডাক্তার ও রোগী ভেরিফিকেশন',
            'desc' => '"Manage Users / Doctors" বাটন থেকে নতুন ডাক্তারদের অ্যাকাউন্ট অনুমোদন (Approve), তথ্য যাচাই বা প্রয়োজনে অ্যাকাউন্ট নিয়ন্ত্রণের সুবিধা পাবেন।',
            'highlight' => 'সাইডবার ➔ Manage Doctors & Users'
        ],
        [
            'icon' => '🚨',
            'badge' => 'ধাপ ৩ / ৪ • কমপ্লেইন ও রিপোর্ট',
            'title' => 'ভুয়া ডাক্তার রিপোর্ট পর্যালোচনা',
            'desc' => '"Fake Doctor Reports" সেকশনে রোগীদের পাঠানো অভিযোগগুলো যাচাই করে তাৎক্ষণিক ব্যবস্থা নিতে পারবেন।',
            'highlight' => 'সাইডবার ➔ Reports'
        ],
        [
            'icon' => '📊',
            'badge' => 'ধাপ ৪ / ৪ • সিস্টেম ওভারভিউ',
            'title' => 'মোট অ্যাপয়েন্টমেন্ট ও ব্লাড ব্যাংক',
            'desc' => 'ড্যাশবোর্ডের মেইন কার্ডগুলো থেকে রিয়েল-টাইমে মোট রোগী, ডাক্তার, অ্যাপয়েন্টমেন্ট এবং ব্লাড ডোনারদের পরিসংখ্যান দেখতে পাবেন।',
            'highlight' => 'সবকিছু প্রস্তুত!'
        ]
    ]
];

$steps =$role_steps[$role] ?? $role_steps['patient'];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CareFlow - Interactive Guide</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Hind Siliguri', system-ui, sans-serif; }
    .fade-slide {
      animation: fadeSlide 0.35s ease-out;
    }
    @keyframes fadeSlide {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-slate-900/80 backdrop-blur-sm flex items-center justify-center min-h-screen p-4">

  <!-- Interactive Tour Modal Box -->
  <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-100">
    
    <!-- Top Progress Bar -->
    <div class="w-full bg-gray-100 h-2">
      <div id="progressBar" class="bg-teal-600 h-2 transition-all duration-300" style="width: 20%;"></div>
    </div>

    <div class="p-8 space-y-6">
      <!-- Top Header: Step Badge & Skip Button -->
      <div class="flex items-center justify-between">
        <span id="stepBadge" class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full border border-teal-200">
          ধাপ ১
        </span>
        <a href="<?= htmlspecialchars($target_dashboard) ?>" class="text-xs text-gray-400 hover:text-gray-600 font-medium transition">
          স্কিপ করুন (Skip) ✕
        </a>
      </div>

      <!-- Dynamic Step Content -->
      <div id="stepContent" class="text-center space-y-4 py-2 fade-slide">
        <div id="stepIcon" class="w-16 h-16 mx-auto bg-teal-50 rounded-2xl flex items-center justify-center text-4xl shadow-inner">
          👋
        </div>
        
        <h2 id="stepTitle" class="text-2xl font-bold text-gray-800">
          স্বাগতম!
        </h2>
        
        <p id="stepDesc" class="text-sm text-gray-600 leading-relaxed">
          লোড হচ্ছে...
        </p>

        <!-- Simulated Button / Location Indicator -->
        <div class="pt-2">
          <span id="stepHighlight" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded-lg border border-gray-200">
            📍 লোকেশন
          </span>
        </div>
      </div>

      <!-- Step Dots Indicator -->
      <div id="stepDots" class="flex justify-center gap-2 pt-2"></div>

      <!-- Navigation Buttons -->
      <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-100">
        <button id="prevBtn" onclick="changeStep(-1)" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition hidden">
          ← পূর্ববর্তী
        </button>
        
        <button id="nextBtn" onclick="changeStep(1)" class="ml-auto px-6 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
          পরবর্তী বাটন দেখুন ➔
        </button>

        <a id="finishBtn" href="<?= htmlspecialchars($target_dashboard) ?>" class="ml-auto px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition shadow-sm hidden items-center gap-2">
          ড্যাশবোর্ডে প্রবেশ করুন ✓
        </a>
      </div>
    </div>

  </div>

  <script>
    const steps = <?= json_encode($steps, JSON_UNESCAPED_UNICODE) ?>;
    let currentStep = 0;

    const progressBar = document.getElementById('progressBar');
    const stepBadge = document.getElementById('stepBadge');
    const stepContent = document.getElementById('stepContent');