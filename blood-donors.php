<?php
// CareFlow - Emergency Blood Donor Hub
$donors = [
    ["name" => "Md. Saju Rana", "group" => "O+", "phone" => "+880 1712-345678", "location" => "Dinajpur", "last_donated" => "4 Months ago", "status" => "Available"],
    ["name" => "Md. Erad Mia", "group" => "B+", "phone" => "+880 1812-345678", "location" => "Rangpur", "last_donated" => "1 Month ago", "status" => "Rest Period"],
    ["name" => "Joydev Roy", "group" => "A+", "phone" => "+880 1912-345678", "location" => "Dinajpur", "last_donated" => "3 Months ago", "status" => "Available"],
    ["name" => "Salma Mizan Nishi", "group" => "AB+", "phone" => "+880 1612-345678", "location" => "Dhaka", "last_donated" => "5 Months ago", "status" => "Available"],
    ["name" => "Tahmid Islam Roni", "group" => "O-", "phone" => "+880 1512-345678", "location" => "Rajshahi", "last_donated" => "2 Months ago", "status" => "Available"],
    ["name" => "Dr. Tanvir Ahmed", "group" => "A-", "phone" => "+880 1711-987654", "location" => "Sylhet", "last_donated" => "2 Months ago", "status" => "Available"],
    ["name" => "Nusrat Jahan", "group" => "B-", "phone" => "+880 1811-123456", "location" => "Chattogram", "last_donated" => "6 Months ago", "status" => "Available"],
    ["name" => "Ashutosh Roy", "group" => "O+", "phone" => "+880 1713-112233", "location" => "Dinajpur", "last_donated" => "3 Months ago", "status" => "Available"],
    ["name" => "Monira Parvin", "group" => "A+", "phone" => "+880 1914-556677", "location" => "Dinajpur", "last_donated" => "4 Months ago", "status" => "Available"],
    ["name" => "Rakibul Hasan", "group" => "B+", "phone" => "+880 1722-334455", "location" => "Dhaka", "last_donated" => "2 Months ago", "status" => "Available"],
    ["name" => "Sumaiya Akter", "group" => "AB-", "phone" => "+880 1833-445566", "location" => "Gazipur", "last_donated" => "5 Months ago", "status" => "Rest Period"],
    ["name" => "Tanvir Hossain", "group" => "O+", "phone" => "+880 1644-556677", "location" => "Khulna", "last_donated" => "1 Month ago", "status" => "Available"],
    ["name" => "Farhana Yasmin", "group" => "A-", "phone" => "+880 1555-667788", "location" => "Barishal", "last_donated" => "3 Months ago", "status" => "Available"],
    ["name" => "Imran Khan", "group" => "B-", "phone" => "+880 1766-778899", "location" => "Bogura", "last_donated" => "6 Months ago", "status" => "Available"],
    ["name" => "Sadia Afrin", "group" => "O-", "phone" => "+880 1877-889900", "location" => "Comilla", "last_donated" => "4 Months ago", "status" => "Available"],
    ["name" => "Mehedi Hasan", "group" => "AB+", "phone" => "+880 1988-990011", "location" => "Rajshahi", "last_donated" => "2 Months ago", "status" => "Available"],
    ["name" => "Nayeem Islam", "group" => "A+", "phone" => "+880 1799-001122", "location" => "Mymensingh", "last_donated" => "3 Months ago", "status" => "Available"],
    ["name" => "Jannatul Ferdous", "group" => "B+", "phone" => "+880 1611-223344", "location" => "Sylhet", "last_donated" => "1 Month ago", "status" => "Available"],
    ["name" => "Al Amin", "group" => "O+", "phone" => "+880 1822-334455", "location" => "Noakhali", "last_donated" => "5 Months ago", "status" => "Rest Period"],
    ["name" => "Sabrina Sultana", "group" => "A-", "phone" => "+880 1933-445566", "location" => "Jessore", "last_donated" => "4 Months ago", "status" => "Available"]
];

$filter_group = $_GET['group'] ?? '';
$filter_loc = $_GET['location'] ?? '';

$filtered_donors = array_filter($donors, function($d) use ($filter_group, $filter_loc) {
    $match_group = empty($filter_group) || $d['group'] === $filter_group;
    $match_loc = empty($filter_loc) || stripos($d['location'], $filter_loc) !== false;
    return $match_group && $match_loc;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title data-i18n="blood.title">Emergency Blood Donor Hub - CareFlow</title>
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
      <a href="appointments.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.appts">🗓️ My Appointments</a>
      <a href="prescription.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.presc">💊 Prescriptions</a>
      <a href="blood-donors.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-teal-50 text-teal-700 font-semibold transition" data-i18n="side.blood">🩸 Blood Donors</a>
      <a href="report-doctor.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.report">🛡️ Report Doctor</a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition" data-i18n="side.logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Area -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Top Header Navigation -->
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 flex items-center justify-between sticky top-0 z-10 shadow-sm">
      <div class="flex items-center gap-3">
        <button id="menu-btn" type="button" class="md:hidden text-2xl text-gray-700 p-1">☰</button>
        <a href="index.html" class="text-2xl font-bold text-teal-600 md:hidden">Care<span class="text-gray-800">Flow</span></a>
      </div>
      <div class="flex items-center gap-2 md:gap-4 text-sm font-medium">
        <a href="index.html" class="px-3 py-2 hover:text-teal-600 transition" data-i18n="nav.home">Home</a>
        <a href="booking.html" class="px-3 py-2 hover:text-teal-600 transition" data-i18n="nav.book">Book Appointment</a>
        <a href="report-doctor.php" class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition font-semibold" data-i18n="nav.report">Report Doctor</a>
        <a href="patient-dashboard.php" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition" data-i18n="nav.dashboard">Dashboard</a>
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 font-semibold transition">
          বাংলা
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 md:px-8 py-10 space-y-8 w-full flex-1">
      <div>
        <h1 class="text-3xl font-bold text-red-600 flex items-center gap-2">🩸 Emergency Blood Donor Hub</h1>
        <p class="text-gray-600 mt-1">Find blood donors quickly by blood group and location.</p>
      </div>

      <!-- Filter Form -->
      <form method="GET" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 grid gap-4 sm:grid-cols-3 items-end">
        <div>
          <label class="block text-sm font-semibold mb-1">Blood Group</label>
          <select name="group" class="w-full px-4 py-2.5 border rounded-lg bg-white focus:ring-2 focus:ring-red-500">
            <option value="">All Blood Groups</option>
            <option value="A+" <?= $filter_group=='A+'?'selected':'' ?>>A+</option>
            <option value="A-" <?= $filter_group=='A-'?'selected':'' ?>>A-</option>
            <option value="B+" <?= $filter_group=='B+'?'selected':'' ?>>B+</option>
            <option value="B-" <?= $filter_group=='B-'?'selected':'' ?>>B-</option>
            <option value="AB+" <?= $filter_group=='AB+'?'selected':'' ?>>AB+</option>
            <option value="AB-" <?= $filter_group=='AB-'?'selected':'' ?>>AB-</option>
            <option value="O+" <?= $filter_group=='O+'?'selected':'' ?>>O+</option>
            <option value="O-" <?= $filter_group=='O-'?'selected':'' ?>>O-</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Location / District</label>
          <input type="text" name="location" value="<?= htmlspecialchars($filter_loc) ?>" placeholder="e.g. Dinajpur, Dhaka" class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-red-500">
        </div>
        <button type="submit" class="w-full py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">Filter Donors</button>
      </form>

      <!-- Donors Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-bold">
              <tr>
                <th class="px-6 py-3">Donor Name</th>
                <th class="px-6 py-3">Blood Group</th>
                <th class="px-6 py-3">Phone Number</th>
                <th class="px-6 py-3">Location</th>
                <th class="px-6 py-3">Last Donated</th>
                <th class="px-6 py-3">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php if (count($filtered_donors) > 0): ?>
                <?php foreach ($filtered_donors as $d): ?>
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($d['name']) ?></td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 bg-red-100 text-red-700 font-bold rounded-full text-xs"><?= htmlspecialchars($d['group']) ?></span></td>
                    <td class="px-6 py-4 text-teal-600 font-semibold"><?= htmlspecialchars($d['phone']) ?></td>
                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($d['location']) ?></td>
                    <td class="px-6 py-4 text-gray-500"><?= htmlspecialchars($d['last_donated']) ?></td>
                    <td class="px-6 py-4">
                      <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $d['status']=='Available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                        <?= htmlspecialchars($d['status']) ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No blood donors found for this filter criteria.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Translation Library -->
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