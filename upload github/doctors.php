<?php
// CareFlow - Doctor Information & Schedule Management
$doctors = [
    ["name" => "Dr. Rahman", "specialty" => "Cardiologist", "dept" => "Cardiology", "exp" => "10 Years", "availability" => "Sun & Tue (5 PM - 8 PM)"],
    ["name" => "Dr. Karim", "specialty" => "Neurologist", "dept" => "Neurology", "exp" => "8 Years", "availability" => "Mon & Wed (4 PM - 7 PM)"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Management - CareFlow</title>
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
        <a href="blood-donors.php" class="px-3 py-2 hover:text-teal-600">Blood Donors</a>
        <a href="report-doctor.php" class="px-3 py-2 hover:text-teal-600">Report Doctor</a>
      </div>
    </nav>
  </header>

  <main class="max-w-5xl mx-auto px-4 py-10 space-y-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">🩺 Doctor Management & Schedule</h1>
      <p class="text-gray-600 mt-1">Doctor ID, Specialization, Dept, Qualification, Fee, Available Days/Time[cite: 12].</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-bold">
          <tr>
            <th class="px-6 py-3">Doctor Name</th>
            <th class="px-6 py-3">Specialization</th>
            <th class="px-6 py-3">Department</th>
            <th class="px-6 py-3">Experience</th>
            <th class="px-6 py-3">Availability</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach ($doctors as $doc): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 font-semibold text-gray-900"><?= $doc['name'] ?></td>
              <td class="px-6 py-4 text-gray-700"><?= $doc['specialty'] ?></td>
              <td class="px-6 py-4 text-gray-600"><?= $doc['dept'] ?></td>
              <td class="px-6 py-4 text-gray-600"><?= $doc['exp'] ?></td>
              <td class="px-6 py-4 font-semibold text-teal-700"><?= $doc['availability'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>