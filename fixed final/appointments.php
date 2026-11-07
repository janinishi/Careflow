<?php
/**
 * CareFlow - Appointments Module
 */
session_start();

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

// Exact sample data matching your screenshot design style
$appointments = [
    [
        'id' => 1,
        'doctor_name' => 'Dr. Rahman',
        'reason' => 'Chest Pain & Followup',
        'appointment_date' => '2026-03-30',
        'status' => 'Approved'
    ],
    [
        'id' => 2,
        'doctor_name' => 'Dr. Karim',
        'reason' => 'Severe Migraine',
        'appointment_date' => '2026-03-31',
        'status' => 'Pending'
    ],
    [
        'id' => 3,
        'doctor_name' => 'Dr. M. A. Wahab',
        'reason' => 'Routine Checkup',
        'appointment_date' => '2026-03-28',
        'status' => 'Completed'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointment Management - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="max-w-6xl mx-auto px-4 py-10 space-y-6">
    
    <!-- Top Header & Back Button -->
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
      <div>
        <h1 class="text-3xl font-bold text-teal-700">Appointment Management</h1>
        <p class="text-sm text-gray-600 mt-1">Here you can view and manage all appointments based on your role (<span class="font-bold text-teal-600"><?= htmlspecialchars($role) ?></span>).</p>
      </div>
      <a href="patient-dashboard.html" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg text-sm transition">Back to Dashboard</a>
    </div>

    <?php if (!empty($message)): ?>
      <div class="p-4 bg-teal-50 text-teal-700 rounded-xl text-sm font-semibold border border-teal-200"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Left 2 Columns: Appointments Table matching screenshot style -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4">
        <h2 class="text-lg font-bold text-gray-900">Appointment History</h2>
        
        <div class="overflow-x-auto rounded-xl border border-gray-100">
          <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
              <tr>
                <th class="px-6 py-4">DOCTOR NAME</th>
                <th class="px-6 py-4">REASON / CAUSE</th>
                <th class="px-6 py-4">DATE</th>
                <th class="px-6 py-4">STATUS</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php foreach ($appointments as $app): ?>
                <tr class="hover:bg-gray-50/50">
                  <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($app['doctor_name']) ?></td>
                  <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($app['reason']) ?></td>
                  <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($app['appointment_date']) ?></td>
                  <td class="px-6 py-4">
                    <?php 
                      $st = $app['status'];
                      // Exact badge styles from screenshot
                      if ($st == 'Approved') {
                          $badgeClass = 'bg-emerald-100 text-emerald-800 px-4 py-1.5 rounded-full font-semibold';
                      } elseif ($st == 'Pending') {
                          $badgeClass = 'bg-amber-100 text-amber-800 px-4 py-1.5 rounded-full font-semibold';
                      } else {
                          $badgeClass = 'bg-blue-100 text-blue-700 px-4 py-1.5 rounded-full font-semibold';
                      }
                    ?>
                    <span class="inline-block text-xs <?= $badgeClass ?>">
                      <?= $st ?>
                    </span>
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
          <h2 class="text-lg font-bold text-gray-900">📅 Book New Appointment</h2>
          <p class="text-xs text-gray-500">Select doctor and schedule your visit.</p>

          <form method="POST" class="space-y-4">
            <input type="hidden" name="book_appointment" value="1">
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Select Doctor</label>
              <select name="doctor_name" required class="w-full px-3 py-2 text-sm border rounded-lg bg-white focus:ring-2 focus:ring-teal-500">
                <option value="Dr. Rahman">Dr. Rahman</option>
                <option value="Dr. Karim">Dr. Karim</option>
                <option value="Dr. M. A. Wahab">Dr. M. A. Wahab</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Appointment Date</label>
              <input type="date" name="appointment_date" required class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
              <label class="block text-xs font-semibold text-gray-700 mb-1">Reason / Cause</label>
              <input type="text" name="reason" required placeholder="e.g. Chest Pain & Followup" class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <button type="submit" class="w-full py-2.5 bg-teal-600 text-white font-semibold text-sm rounded-lg hover:bg-teal-700 transition shadow-sm">Confirm Booking</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</body>
</html>