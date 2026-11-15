<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$display_name = $_SESSION['full_name'] ?? 'User';
$role = $_SESSION['role'] ?? 'patient';
$dashboards = ['patient' => 'patient-dashboard.php', 'doctor' => 'doctor-dashboard.php', 'admin' => 'admin-dashboard.php'];
$target_dashboard = $dashboards[$role] ?? 'patient-dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CareFlow - Auto Welcome Tour</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Inter', 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-900/80 flex items-center justify-center min-h-screen p-4">
  <div class="bg-white max-w-3xl w-full rounded-3xl shadow-2xl p-6 md:p-8 space-y-6">
    <div class="flex items-center justify-between border-b pb-4">
      <h1 class="text-xl font-bold text-teal-700">Welcome, <?= htmlspecialchars($display_name) ?>!</h1>
      <a href="<?= htmlspecialchars($target_dashboard) ?>" class="text-xs text-gray-400 hover:text-gray-600">Skip Tour ✕</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
      <div class="space-y-2.5" id="btnList"></div>
      <div class="bg-teal-50/70 border border-teal-100 rounded-2xl p-6 space-y-3 min-h-[200px] flex flex-col justify-center">
        <span class="text-xs font-bold uppercase tracking-wider text-teal-600">Auto Tour Preview ➔</span>
        <h3 id="tourTitle" class="text-lg font-bold text-gray-800"></h3>
        <p id="tourEn" class="text-sm text-gray-700"></p>
        <p id="tourBn" class="text-sm text-teal-900 bg-white p-3 rounded-xl border border-teal-100"></p>
      </div>
    </div>

    <div class="pt-4 border-t flex justify-end">
      <a href="<?= htmlspecialchars($target_dashboard) ?>" class="px-6 py-2.5 bg-teal-600 text-white text-sm font-semibold rounded-xl hover:bg-teal-700 shadow">
        Continue to Dashboard ➔
      </a>
    </div>
  </div>

  <script>
    const role = <?= json_encode($role) ?>;
    const dataMap = {
      patient: [
        { label: '📅 Book Appointment', title: 'Doctor Appointment', en: 'Search specialist doctors and book an online slot.', bn: 'বিশেষজ্ঞ ডাক্তার খুঁজে অনলাইনে সিরিয়াল বুক করুন।' },
        { label: '💊 My Prescriptions', title: 'Digital Prescriptions', en: 'View and download all doctor-issued prescriptions.', bn: 'ডাক্তারদের দেওয়া সব ডিজিটাল প্রেসক্রিপশন দেখুন ও ডাউনলোড করুন।' },
        { label: '🩸 Blood Donors', title: 'Find Blood Donors', en: 'Find verified blood donors during emergencies.', bn: 'জরুরি রক্তের প্রয়োজনে গ্রুপ অনুযায়ী ডোনার খুঁজুন।' },
        { label: '🛡️ Report Doctor', title: 'Safety & Report', en: 'Report fake doctors or submit feedback directly to admin.', bn: 'ভুয়া ডাক্তার বা যেকোনো সমস্যা সরাসরি অ্যাডমিনকে রিপোর্ট করুন।' }
      ],
      doctor: [
        { label: '📋 Today’s Appointments', title: 'Patient Serials', en: 'View scheduled patients and manage consultation status.', bn: 'আজকের রোগীদের সিরিয়াল দেখুন এবং কনসালটেশন আপডেট করুন।' },
        { label: '✍️ Write Prescription', title: 'E-Prescription', en: 'Create digital prescriptions that patients receive instantly.', bn: 'রোগীদের জন্য ডিজিটাল প্রেসক্রিপশন লিখে সাবমিট করুন।' },
        { label: '⚙️ Chamber Settings', title: 'Schedule & Fees', en: 'Update your visiting hours and consultation fees.', bn: 'আপনার রোগী দেখার সময়সূচি, চেম্বার ও ফি আপডেট করুন।' }
      ],
      admin: [
        { label: '👥 Manage Users', title: 'User & Doctor Control', en: 'Approve new doctor registrations and manage accounts.', bn: 'নতুন ডাক্তারদের অনুমোদন দিন এবং সব ইউজার নিয়ন্ত্রণ করুন।' },
        { label: '🚨 Fake Doctor Reports', title: 'Review Complaints', en: 'Investigate patient complaints against unverified profiles.', bn: 'রোগীদের পাঠানো অভিযোগ যাচাই করে ব্যবস্থা নিন।' }
      ]
    };

    const items = dataMap[role] || dataMap.patient;
    const btnList = document.getElementById('btnList');
    let idx = 0;

    function show(i) {
      idx = i;
      const item = items[idx];
      document.getElementById('tourTitle').textContent = item.title;
      document.getElementById('tourEn').textContent = item.en;
      document.getElementById('tourBn').textContent = item.bn;

      Array.from(btnList.children).forEach((el, index) => {
        el.className = index === idx 
          ? 'w-full text-left px-4 py-3 rounded-xl font-semibold text-sm bg-teal-600 text-white shadow' 
          : 'w-full text-left px-4 py-3 rounded-xl font-semibold text-sm bg-gray-100 text-gray-700 hover:bg-gray-200';
        el.textContent = items[index].label;
      });
    }

    items.forEach((item, index) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.onclick = () => show(index);
      btnList.appendChild(b);
    });

    show(0);
    setInterval(() => {
      idx = (idx + 1) % items.length;
      show(idx);
    }, 3500);
  </script>
</body>
</html>