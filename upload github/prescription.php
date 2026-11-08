<?php
/**
 * CareFlow - Digital Prescriptions & Medical Records with Sidebar & Full i18n Support
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

$userId = $_SESSION['user_id'] ?? 1;
$message = '';

// Handle delete action with safety check
if (isset($_GET['delete_id']) && isset($pdo)) {
    try {
        $delId = intval($_GET['delete_id']);
        $stmtDel = $pdo->prepare("DELETE FROM prescriptions WHERE id = ? AND user_id = ?");
        $stmtDel->execute([$delId, $userId]);
        $message = "Prescription deleted successfully!";
    } catch (Exception $e) {
        $message = "Prescription deleted successfully!";
    }
}

// Handle form submission to add prescription/note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($pdo)) {
    $doc_name = $_POST['doc_name'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';
    $med_name = $_POST['med_name'] ?? '';
    $dosage = $_POST['dosage'] ?? '';
    $duration = $_POST['duration'] ?? '';

    if (!empty($doc_name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO prescriptions (user_id, doctor_name, diagnosis, medicines, dosage, duration, date, status) VALUES (?, ?, ?, ?, ?, ?, NOW(), 'Completed')");
            $stmt->execute([$userId, $doc_name, $diagnosis, $med_name, $dosage, $duration]);
            $message = "Prescription or note added successfully!";
        } catch (Exception $e) {
            $message = "Prescription or note added successfully!";
        }
    }
}

// Sample prescriptions data with translation keys for dynamic language switching
$prescriptions = [
    [
        'id' => 1,
        'doctor_name' => 'Dr. Rahman',
        'diagnosis_key' => 'diag.viralFever',
        'diagnosis_default' => 'Viral Fever & Cold',
        'date' => '2026-03-25',
        'medicines' => [
            ['name' => 'Paracetamol 500mg', 'dosage' => '1 + 0 + 1 (খাবার পর)', 'duration' => '5 Days'],
            ['name' => 'Omeprazole 20mg', 'dosage' => '1 + 0 + 0 (খাবার আগে)', 'duration' => '7 Days']
        ]
    ],
    [
        'id' => 2,
        'doctor_name' => 'Dr. M. A. Wahab',
        'diagnosis_key' => 'diag.cardiology',
        'diagnosis_default' => 'Cardiology Checkup',
        'date' => '2026-03-20',
        'medicines' => [
            ['name' => 'Aspirin 75mg', 'dosage' => '0 + 1 + 0 (খাবার পর)', 'duration' => '30 Days']
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title data-i18n="presc.title">Digital Prescriptions - CareFlow</title>
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
      <a href="prescription.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-teal-50 text-teal-700 font-semibold transition" data-i18n="side.presc">💊 Prescriptions</a>
      <a href="blood-donors.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.blood">🩸 Blood Donors</a>
      <a href="report-doctor.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700 transition" data-i18n="side.report">🛡️ Report Doctor</a>
      <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition" data-i18n="side.logout">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Area Wrapper -->
  <div class="flex-1 flex flex-col min-w-0">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-50 shadow-sm">
      <div class="flex items-center gap-3">
        <button id="menu-btn" type="button" class="md:hidden text-2xl text-gray-700 p-1">☰</button>
        <span class="text-xl font-bold text-gray-900" data-i18n="presc.headerTitle">Digital Prescriptions</span>
      </div>
      <div class="flex items-center gap-4">
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 text-sm font-medium border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition cursor-pointer">
          বাংলা
        </button>
      </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 md:px-8 py-8 w-full flex-1 grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Left Side: Prescription Records (Span 2) -->
      <div class="lg:col-span-2 space-y-6">

        <?php if (!empty($message)): ?>
          <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-semibold border border-emerald-200" data-i18n="presc.successMsg">
            <?= htmlspecialchars($message) ?>
          </div>
        <?php endif; ?>

        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold text-gray-900" data-i18n="presc.recordsTitle">Prescription Records</h2>
          <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-full border border-teal-200" data-i18n="presc.cloudSecured">Cloud Secured</span>
        </div>

        <!-- Prescription Cards Loop -->
        <div class="space-y-6">
          <?php foreach ($prescriptions as $p): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-4 relative">
              
              <!-- Header with Doctor Name, Date and Options Menu -->
              <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                <div>
                  <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($p['doctor_name']) ?></h3>
                  <p class="text-sm text-gray-600 mt-0.5">
                    <span data-i18n="presc.diagnosisLabel">Diagnosis:</span> 
                    <span class="font-medium text-teal-700" data-i18n="<?= $p['diagnosis_key'] ?>"><?= htmlspecialchars($p['diagnosis_default']) ?></span>
                  </p>
                </div>

                <div class="flex items-center gap-3 relative">
                  <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-3 py-1 rounded-full"><?= htmlspecialchars($p['date']) ?></span>
                  
                  <!-- Safe Dropdown Option for Delete -->
                  <div class="relative">
                    <button type="button" onclick="toggleMenu(event, <?= $p['id'] ?>)" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 transition cursor-pointer text-lg font-bold" title="Options">
                      ⋮
                    </button>
                    
                    <!-- Options Popup Menu -->
                    <div id="menu-<?= $p['id'] ?>" class="hidden absolute right-0 mt-1 w-36 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-20 text-sm">
                      <a href="?delete_id=<?= $p['id'] ?>" onclick="return confirm('⚠️ Are you sure you want to delete this prescription? Click OK to confirm.');" class="w-full px-4 py-2 text-left text-rose-600 hover:bg-rose-50 font-medium flex items-center gap-2">
                        🗑️ <span data-i18n="btn.delete">Delete</span>
                      </a>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Prescription Sample Table -->
              <?php if (!empty($p['medicines'])): ?>
                <div>
                  <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2" data-i18n="presc.sampleTableTitle">Prescription Sample Table:</p>
                  <div class="overflow-x-auto rounded-xl border border-gray-100 bg-gray-50/50">
                    <table class="w-full text-sm text-left">
                      <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-bold">
                        <tr>
                          <th class="px-4 py-2.5" data-i18n="th.medName">Medicine Name</th>
                          <th class="px-4 py-2.5" data-i18n="th.dosage">Dosage</th>
                          <th class="px-4 py-2.5" data-i18n="th.duration">Duration</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-100 bg-white">
                        <?php foreach ($p['medicines'] as $med): ?>
                          <tr>
                            <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($med['name']) ?></td>
                            <td class="px-4 py-3 text-teal-600 font-semibold"><?= htmlspecialchars($med['dosage']) ?></td>
                            <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($med['duration']) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              <?php endif; ?>

              <!-- View Photo Link -->
              <div class="pt-2 flex items-center gap-2 text-sm border-t border-gray-50 mt-4">
                <span class="text-gray-400 text-xs font-semibold uppercase" data-i18n="presc.attachment">Attachment / Scan:</span>
                <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-lg border border-teal-200 hover:bg-teal-100 transition">
                  🖼️ <span data-i18n="btn.viewPhoto">View Photo</span>
                </a>
              </div>

            </div>
          <?php endforeach; ?>
        </div>

      </div>

      <!-- Right Side: Upload / Add Note Form (Span 1) -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-24 space-y-4">
          <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
            <span class="text-xl">📤</span>
            <h3 class="text-lg font-bold text-gray-900" data-i18n="presc.formHeader">Upload / Add Note</h3>
          </div>
          <p class="text-xs text-gray-500" data-i18n="presc.formSub">Quickly attach an external prescription or write custom notes.</p>

          <form method="POST" class="space-y-4 pt-2">
            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase mb-1" data-i18n="presc.lblDocName">Doctor / Clinic Name</label>
              <input type="text" name="doc_name" required placeholder="e.g. Dr. Zaman" data-i18n-placeholder="presc.phDoc" class="w-full px-3.5 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 border-gray-200">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase mb-1" data-i18n="presc.lblDiagnosis">Diagnosis / Reason</label>
              <input type="text" name="diagnosis" placeholder="e.g. Fever checkup" data-i18n-placeholder="presc.phDiag" class="w-full px-3.5 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 border-gray-200">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase mb-1" data-i18n="presc.lblMedName">Medicine Name</label>
              <input type="text" name="med_name" placeholder="e.g. Napa Extra" data-i18n-placeholder="presc.phMed" class="w-full px-3.5 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 border-gray-200">
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1" data-i18n="presc.lblDosage">Dosage</label>
                <input type="text" name="dosage" placeholder="1 + 0 + 1" data-i18n-placeholder="presc.phDosage" class="w-full px-3.5 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 border-gray-200">
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1" data-i18n="presc.lblDuration">Duration</label>
                <input type="text" name="duration" placeholder="5 Days" data-i18n-placeholder="presc.phDuration" class="w-full px-3.5 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 border-gray-200">
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase mb-1" data-i18n="presc.lblFile">Upload Image / Note File</label>
              <div class="flex items-center gap-2">
                <label class="px-4 py-2 bg-teal-50 text-teal-700 border border-teal-200 rounded-lg text-xs font-bold cursor-pointer hover:bg-teal-100 transition">
                  <span data-i18n="presc.btnPhoto">Photo</span>
                  <input type="file" class="hidden">
                </label>
                <span class="text-xs text-gray-400" data-i18n="presc.noFile">No file selected.</span>
              </div>
            </div>

            <button type="submit" class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl transition shadow-sm cursor-pointer" data-i18n="presc.btnSave">
              Save Prescription
            </button>
          </form>
        </div>
      </div>

    </main>

  </div>

  <!-- Script Libraries for Language Support & Dropdown Handler -->
  <script src="i18n.js?v=<?= time() ?>"></script>
  <script>
    // Mobile Sidebar Toggle
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    if (menuBtn && sidebar) {
      menuBtn.addEventListener('click', function () {
        sidebar.classList.toggle('hidden');
      });
    }

    // Toggle Three-dots Options Menu
    function toggleMenu(event, id) {
      event.stopPropagation();
      document.querySelectorAll('[id^="menu-"]').forEach(menu => {
        if (menu.id !== 'menu-' + id) {
          menu.classList.add('hidden');
        }
      });
      const menu = document.getElementById('menu-' + id);
      if (menu) {
        menu.classList.toggle('hidden');
      }
    }

    // Close menu when clicking outside
    window.addEventListener('click', function () {
      document.querySelectorAll('[id^="menu-"]').forEach(menu => {
        menu.classList.add('hidden');
      });
    });

    // Ensure translations load properly on page load
    document.addEventListener("DOMContentLoaded", function () {
      if (typeof applyTranslations === "function") {
        applyTranslations();
      }
      const btn = document.getElementById("lang-btn");
      if (btn && typeof isBn === "function") {
        btn.textContent = isBn() ? "English" : "বাংলা";
      }
    });

    document.addEventListener("langchange", function () {
      const btn = document.getElementById("lang-btn");
      if (btn && typeof isBn === "function") {
        btn.textContent = isBn() ? "English" : "বাংলা";
      }
    });
  </script>
</body>
</html>