<?php
// CareFlow - Appointment Management System with Session Filtering
session_start();

// Database Connection Setup (XAMPP default MySQL/MariaDB)
$host = 'localhost';$db   = 'careflow_db';
$user = 'root';$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user,$pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db`");

    // Create appointments table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS appointments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_name VARCHAR(100) NOT NULL,
        doctor_name VARCHAR(100) NOT NULL,
        reason VARCHAR(255) NOT NULL,
        appointment_date DATE NOT NULL,
        status ENUM('Pending', 'Approved', 'Completed') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Insert sample data if table is empty
    $stmt =$pdo->query("SELECT COUNT(*) FROM appointments");
    if ($stmt->fetchColumn() == 0) {$pdo->exec("INSERT INTO appointments (patient_name, doctor_name, reason, appointment_date, status) VALUES 
            ('Salma Mizan Nishi', 'Dr. Rahman', 'Chest Pain & Followup', '2026-03-30', 'Approved'),
            ('Md. Saju Rana', 'Dr. Karim', 'Severe Migraine', '2026-03-31', 'Pending'),
            ('Salma Mizan Nishi', 'Dr. M. A. Wahab', 'Routine Checkup', '2026-03-28', 'Completed')");
    }
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Current Logged-in User Session Demo
$current_user =$_SESSION['username'] ?? 'Salma Mizan Nishi';

if (isset($_POST['switch_user'])) {
    $_SESSION['username'] =$_POST['switch_user'];
    header("Location: appointments.php");
    exit();
}

// Handle New Appointment Booking
$success_msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $doc =$_POST['doctor_name'] ?? '';
    $reason =$_POST['reason'] ?? '';
    $date =$_POST['appointment_date'] ?? '';

    if (!empty($doc) && !empty($reason) && !empty($date)) {
        $stmt =$pdo->prepare("INSERT INTO appointments (patient_name, doctor_name, reason, appointment_date, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$current_user, $doc,$reason, $date]);$success_msg = "Appointment booked successfully!";
    }
}

// Fetch appointments specific to current user session
$stmt =$pdo->prepare("SELECT * FROM appointments WHERE patient_name = ? ORDER BY appointment_date DESC");
$stmt->execute([$current_user]);
$appointments =$stmt->fetchAll();
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
  
  <header class="bg-white shadow-sm border-b">
    <nav class="max-w-6xl mx-auto flex items-center justify-between px-4 py-3">
      <a href="index.html" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>
      <div class="flex items-center gap-4">
        <form method="POST" class="flex items-center gap-2">
          <span class="text-xs font-semibold text-gray-500">User:</span>
          <select name="switch_user" onchange="this.form.submit()" class="text-xs px-2 py-1 border rounded-lg bg-gray-50 font-bold text-teal-700">
            <option value="Salma Mizan Nishi" <?= $current_user=='Salma Mizan Nishi'?'selected':'' ?>>Salma Mizan Nishi</option>
            <option value="Md. Saju Rana" <?= $current_user=='Md. Saju Rana'?'selected':'' ?>>Md. Saju Rana</option>
          </select>
        </form>
        <a href="prescription.php" class="px-3 py-1.5 bg-teal-600 text-white rounded-lg text-xs font-semibold hover:bg-teal-700 transition">Prescriptions</a>
      </div>
    </nav>
  </header>

  <main class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left 2 Cols: Appointments Table -->
    <div class="lg:col-span-2 space-y-6">
      <?php if (!empty($success_msg)): ?>
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-semibold">
          <?= $success_msg ?>
        </div>
      <?php endif; ?>

      <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex justify-between items-center">
        <div>
          <h1 class="text-lg font-bold text-gray-900">Appointments for: <span class="text-teal-600"><?= htmlspecialchars($current_user) ?></span></h1>
          <p class="text-xs text-gray-500">Real-time status tracking (Pending, Approved, Completed)[cite: 13].</p>
        </div>
        <span class="text-xs bg-blue-100 text-blue-800 font-semibold px-3 py-1 rounded-full">Session Active</span>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-bold">
              <tr>
                <th class="px-4 py-3">Doctor Name</th>
                <th class="px-4 py-3">Reason / Cause</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php if (count($appointments) > 0): ?>
                <?php foreach ($appointments as$app): ?>
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold text-gray-900"><?= htmlspecialchars($app['doctor_name']) ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($app['reason']) ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($app['appointment_date']) ?></td>
                    <td class="px-4 py-3">
                      <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                        <?= $app['status']=='Approved' ? 'bg-green-100 text-green-700' : ($app['status']=='Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-700') ?>">
                        <?= $app['status'] ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No appointments found for <?= htmlspecialchars($current_user) ?>.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Right 1 Col: Book Appointment Form -->
    <div>
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
        <h2 class="text-lg font-bold text-gray-900 mb-2">📅 Book New Appointment</h2>
        <p class="text-xs text-gray-500 mb-4">Ensure real-time serial booking confirmation[cite: 13].</p>

        <form method="POST" class="space-y-4">
          <input type="hidden" name="book_appointment" value="1">
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Select Doctor</label>
            <select name="doctor_name" required class="w-full px-3 py-2 text-sm border rounded-lg bg-white">
              <option value="Dr. Rahman">Dr. Rahman (Cardiologist)</option>
              <option value="Dr. Karim">Dr. Karim (Neurologist)</option>
              <option value="Dr. M. A. Wahab">Dr. M. A. Wahab (Cardiology)</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Main Reason / Cause</label>
            <input type="text" name="reason" required placeholder="e.g. Fever & Headache" class="w-full px-3 py-2 text-sm border rounded-lg">
          </div>
          <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Appointment Date</label>
            <input type="date" name="appointment_date" required class="w-full px-3 py-2 text-sm border rounded-lg">
          </div>
          <button type="submit" class="w-full py-2.5 bg-teal-600 text-white font-semibold text-sm rounded-lg hover:bg-teal-700 transition shadow-sm">Confirm Appointment</button>
        </form>
      </div>
    </div>

  </main>
</body>
</html>