<?php
/**
 * CareFlow - Dynamic Patient Dashboard with Perfect Website Tour Integration
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

// সেশন বা ডাটাবেস থেকে ইউজারের আসল নাম এবং রোল নিশ্চিত করা
$display_name = $_SESSION['full_name'] ?? $_SESSION['name'] ?? 'Patient';
$role =$_SESSION['role'] ?? 'patient';

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt =$pdo->prepare("SELECT full_name, name, role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user =$stmt->fetch();
        if ($user) {
            $display_name =$user['full_name'] ?? $user['name'] ?? $display_name;
            $role = $user['role'] ?? $role;
        }
    } catch (Exception $e) {
        // Fallback to session name
    }
}

// নামের প্রথম অক্ষর (Avatar-এর জন্য)
$first_letter = strtoupper(mb_substr($display_name, 0, 1));
$start_tour = isset($_GET['tour']) && $_GET['tour'] == '1' &&$role !== 'admin';
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
    
    /* স্পটলাইট ট্যুর হাইলাইট ইফেক্ট */
    .tour-highlight {
      position: relative !important;
      z-index: 100 !important;
      background-color: #ffffff !important;
      box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.4), 0 10px 25px -3px rgba(0, 0, 0, 0.2) !important;
      border-radius: 0.5rem;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col md:flex-row relative">

  <!-- Sidebar -->
  <aside id="sidebar" class="hidden md:block w-full md:w-64 bg-white border-r border-gray-200 shrink-0 min-h-screen">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <a href="index.html" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>
    </div>

    <nav class="p-4 space-y-1 text-sm font-medium">
      <a href="patient-dashboard.php" id="tour-dash" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-teal-50 text-teal-700 font-semibold" data-i18n="side.dash">🏠 Dashboard</a>
      
      <?php if ($role === 'patient'): ?>
        <a href="booking.html" id="tour-book" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700" data-i18n="side.book">📅 Book Appointment</a>
        <a href="appointments.php" id="tour-appts" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700" data-i18n="side.appts">🗓️ My Appointments</a>
        <a href="prescription.php" id="tour-presc" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700" data-i18n="side.presc">💊 Prescriptions</a>
        <a href="blood-donors.php" id="tour-blood" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700" data-i18n="side.blood">🩸 Blood Donors</a>
        <a href="report-doctor.php" id="tour-report" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700" data-i18n="side.report">🛡️ Report Doctor</a>
      <?php elseif ($role === 'doctor'): ?>
        <a href="appointments.php" id="tour-doc-appts" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700">🗓️ Patient Appointments</a>
        <a href="prescription.php" id="tour-doc-presc" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700">✍️ Write Prescription</a>
      <?php elseif ($role === 'donor'): ?>
        <a href="blood-donors.php" id="tour-donor-req" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700">🩸 Blood Requests</a>
      <?php endif; ?>

      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50" data-i18n="side.logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Area -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-4 flex items-center justify-between sticky top-0 z-70">
      <div class="flex items-center gap-3">
        <button id="menu-btn" type="button" class="md:hidden text-2xl text-gray-700 p-1">☰</button>
        <h1 class="text-xl font-bold text-gray-800" data-i18n="dash.h1">Patient Dashboard</h1>
      </div>
      <div class="flex items-center gap-3 relative z-70">
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 text-sm font-medium border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition cursor-pointer">
          বাংলা
        </button>
        <!-- ডাইনামিক ইউজারের নাম -->
        <span class="hidden sm:block text-sm text-gray-600 font-medium">Hello, <strong class="text-gray-900"><?= htmlspecialchars($display_name) ?></strong></span>
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
                <th class="px-6 py-3" data-i18n="th.dx">Diagnosis</th>
                <th class="px-6 py-3" data-i18n="th.meds">Medicines</th>
                <th class="px-6 py-3" data-i18n="th.status">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">12 Oct 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold">Dr. Anika Rahman</td>
                <td class="px-6 py-4 text-gray-600">Hypertension</td>
                <td class="px-6 py-4 text-gray-600">Amlodipine 5mg</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700" data-i18n="status.completed">Completed</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">28 Sep 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold">Dr. Karim Hossain</td>
                <td class="px-6 py-4 text-gray-600">Seasonal Flu</td>
                <td class="px-6 py-4 text-gray-600">Paracetamol 500mg</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700" data-i18n="status.completed">Completed</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">25 Oct 2026</td>
                <td class="px-6 py-4 text-gray-900 font-semibold">Dr. Anika Rahman</td>
                <td class="px-6 py-4 text-gray-400">-</td>
                <td class="px-6 py-4 text-gray-400">-</td>
                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700" data-i18n="status.pending">Pending</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

    </main>
  </div>

  <!-- Interactive Website Tour Overlay Box -->
  <?php if ($start_tour): ?>
  <div id="tour-overlay" class="fixed inset-0 bg-black/20 z-50 pointer-events-none flex items-center justify-center p-4">
    <div id="tour-card" class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4 border border-teal-100 transform transition-all pointer-events-auto relative z-90">
      <div class="flex items-center justify-between border-b pb-3">
        <span id="tour-step-indicator" class="text-xs font-bold text-teal-600 uppercase tracking-wider">Step 1 of 4</span>
        <button onclick="endTour()" class="text-gray-400 hover:text-gray-600 text-sm font-semibold cursor-pointer">Skip Tour ✕</button>
      </div>
      <div class="space-y-2">
        <h3 id="tour-title" class="text-lg font-bold text-gray-900">Welcome to CareFlow Dashboard!</h3>
        <p id="tour