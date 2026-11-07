<?php
/**
 * CareFlow - Appointments Module with Full Language Support
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = $_SESSION['user_id'] ?? 1;
$role = $_SESSION['role'] ?? 'patient';
$message = '';

// New appointment booking handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $doctorName = $_POST['doctor_name'] ?? 'Dr. Rahman';
    $date       = $_POST['appointment_date'] ?? date('Y-m-d');
    $reason     = trim($_POST['reason'] ?? 'General Checkup');

    if (empty($date) || empty($reason)) {
        $message = 'Please fill in all required appointment fields.';
    } else {
        $message = 'Appointment booked successfully!';
    }
}

// Sample appointments with translation keys for doctors, reasons and statuses
$appointments = [
    [
        'id' => 1,
        'doc_key' => 'doc.rahman',
        'reason_key' => 'reason.chest',
        'appointment_date' => '2026-03-30',
        'status_key' => 'status.approved',
        'badge' => 'bg-emerald-100 text-emerald-800'
    ],
    [
        'id' => 2,
        'doc_key' => 'doc.karim',
        'reason_key' => 'reason.migraine',
        'appointment_date' => '2026-03-31',
        'status_key' => 'status.pending',
        'badge' => 'bg-amber-100 text-amber-800'
    ],
    [
        'id' => 3,
        'doc_key' => 'doc.wahab',
        'reason_key' => 'reason.routine',
        'appointment_date' => '2026-03-28',
        'status_key' => 'status.completed',
        'badge' => 'bg-blue-100 text-blue-700'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title data-i18n="appts.title">Appointments - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col md:flex-row">

  <!-- Sidebar Navigation -->
  <aside id="sidebar" class="hidden md:block w-full md:w-64 bg-white border-r border-gray-200 shrink-0 min-h-screen shadow-sm">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <a href="index.html" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>
    </div>

    <nav class="p-4 space-y-1 text-sm font-medium">
      <a href="patient-dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.dash">🏠 Dashboard</a>
      <a href="booking.html" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.book">📅 Book Appointment</a>
      <a href="appointments.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-teal-50 text-teal-700 font-semibold transition" data-i18n="side.appts">🗓️ My Appointments</a>
      <a href="prescription.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.presc">💊 Prescriptions</a>
      <a href="blood-donors.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.blood">🩸 Blood Donors</a>
      <a href="report-doctor.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.report">🛡️ Report Doctor</a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition" data-i18n="side.logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Area -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Top Header with Language Toggle Button -->
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 flex items-center justify-between sticky top-0 z-10 shadow-sm">
      <div class="flex items-center gap-3">
        <button id="menu-btn" type="button" class="md:hidden text-2xl text-gray-700 p-1">☰</button>
        <span class="text-xl font-bold text-gray-800 md:hidden">CareFlow</span>
      </div>
      <div class="flex items-center gap-3">
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 text-sm font-medium border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition cursor-pointer">
          বাংলা
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-10 space-y-6 w-full flex-1">
      
      <!-- Top Header & Back Button -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        <div>
          <h1 class="text-3xl font-bold text-teal-700" data-i18n="appts.h1">Appointments</h1>
          <p class="text-sm text-gray-600 mt-1">Here you can view and manage all appointments based on your role (<span class="font-bold text-teal-600"><?= htmlspecialchars($role) ?></span>).</p>
        </div>
        <a href="patient-dashboard.php" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition" data-i18n="appts.back">Back to Dashboard</a>
      </div>

      <?php if (!empty($message)): ?>
        <div class="p-4 bg-teal-50 text-teal-700 rounded-xl text-sm font-semibold border border-teal-200"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Appointments Table -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
          <h2 class="text-lg font-bold text-gray-900" data-i18n="appts.history">Appointment History</h2>
          
          <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-sm text-left">
              <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
                <tr>
                  <th class="px-6 py-4" data-i18n="th.doctor">DOCTOR NAME</th>
                  <th class="px-6 py-4" data-i18n="appts.reason">REASON / CAUSE</th>
                  <th class="px-6 py-4" data-i18n="th.date">DATE</th>
                  <th class="px-6 py-4" data-i18n="th.status">STATUS</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php foreach ($appointments as $app): ?>
                  <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-semibold text-gray-900" data-i18n="<?= $app['doc_key'] ?>"></td>
                    <td class="px-6 py-4 text-gray-600" data-i18n="<?= $app['reason_key'] ?>"></td>
                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($app['appointment_date']) ?></td>
                    <td class="px-6 py-4">
                      <span class="inline-block text-xs px-4 py-1.5 rounded-full font-semibold <?= $app['badge'] ?>" data-i18n="<?= $app['status_key'] ?>"></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Right 1 Column: Quick Booking Form -->
        <div>
          <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4 sticky top-6">
            <h2 class="text-lg font-bold text-gray-900" data-i18n="appts.bookNew">📅 Book New Appointment</h2>
            <p class="text-xs text-gray-500" data-i18n="appts.bookSub">Select doctor and schedule your visit.</p>

            <form method="POST" class="space-y-4">
              <input type="hidden" name="book_appointment" value="1">
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1" data-i18n="appts.selDoc">Select Doctor</label>
                <select name="doctor_name" required class="w-full px-3 py-2 text-sm border rounded-lg bg-white focus:ring-2 focus:ring-teal-500">
                  <option value="Dr. Rahman">Dr. Rahman</option>
                  <option value="Dr. Karim">Dr. Karim</option>
                  <option value="Dr. M. A. Wahab">Dr. M. A. Wahab</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1" data-i18n="appts.date">Appointment Date</label>
                <input type="date" name="appointment_date" required class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-teal-500">
              </div>
              <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1" data-i18n="appts.cause">Reason / Cause</label>
                <input type="text" name="reason" required placeholder="e.g. Chest Pain & Followup" class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-teal-500">
              </div>
              <button type="submit" class="w-full py-2.5 bg-teal-600 text-white font-semibold text-sm rounded-lg hover:bg-teal-700 transition shadow-sm" data-i18n="appts.confirm">Confirm Booking</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="i18n.js"></script>
  <script>
    // Mobile Sidebar Toggle
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    if (menuBtn && sidebar) {
      menuBtn.addEventListener('click', function () {
        sidebar.classList.toggle('hidden');
      });
    }

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