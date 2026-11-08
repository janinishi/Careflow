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
  <title>Emergency Blood Donor Hub - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
  <header class="bg-white shadow-sm border-b">
    <nav class="max-w-6xl mx-auto flex items-center justify-between px-4 py-3">
      <a href="index.html" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>
      <div class="flex items-center gap-3 text-sm font-medium">
        <a href="index.html" class="px-3 py-2 hover:text-teal-600">Home</a>
        <a href="booking.html" class="px-3 py-2 hover:text-teal-600">Book Appointment</a>
        <a href="patient-dashboard.html" class="px-4 py-2 bg-teal-600 text-white rounded-lg">Dashboard</a>
      </div>
    </nav>
  </header>

  <main class="max-w-5xl mx-auto px-4 py-10 space-y-8">
    <div>
      <h1 class="text-3xl font-bold text-red-600 flex items-center gap-2">🩸 Emergency Blood Donor Hub</h1>
      <p class="text-gray-600 mt-1">Find blood donors quickly by blood group and location[cite: 8].</p>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 grid gap-4 sm:grid-cols-3 items-end">
      <div>
        <label class="block text-sm font-semibold mb-1">Blood Group</label>
        <select name="group" class="w-full px-4 py-2.5 border rounded-lg bg-white">
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
        <input type="text" name="location" value="<?= htmlspecialchars($filter_loc) ?>" placeholder="e.g. Dinajpur, Dhaka" class="w-full px-4 py-2.5 border rounded-lg">
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
                  <td class="px-6 py-4 font-semibold text-gray-900"><?= $d['name'] ?></td>
                  <td class="px-6 py-4"><span class="px-2.5 py-1 bg-red-100 text-red-700 font-bold rounded-full text-xs"><?= $d['group'] ?></span></td>
                  <td class="px-6 py-4 text-teal-600 font-semibold"><?= $d['phone'] ?></td>
                  <td class="px-6 py-4 text-gray-600"><?= $d['location'] ?></td>
                  <td class="px-6 py-4 text-gray-500"><?= $d['last_donated'] ?></td>
                  <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $d['status']=='Available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                      <?= $d['status'] ?>
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
</body>
</html>