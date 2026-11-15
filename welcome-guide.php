<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$display_name = $_SESSION['full_name'] ?? 'User';
$role = $_SESSION['role'] ?? 'patient';
$target_dashboard = ($role === 'admin') ? 'admin-dashboard.php' : (($role === 'doctor') ? 'doctor-dashboard.php' : 'patient-dashboard.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CareFlow - Interactive Button Explorer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Inter', 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
  <div class="bg-white max-w-3xl w-full rounded-3xl shadow-xl p-6 md:p-8 space-y-6">
    <div class="text-center">
      <h1 class="text-2xl font-bold text-teal-700">Welcome, <?= htmlspecialchars($display_name) ?>! (<?= ucfirst($role) ?>)</h1>
      <p class="text-sm text-gray-500">Click any button below to see what it does / নিচের যেকোনো বাটনে ক্লিক করে তার কাজ দেখুন</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
      <!-- বাম পাশে ক্লিকযোগ্য বাটন লিস্ট -->
      <div class="space-y-2.5" id="btnList"></div>

      <!-- ডান পাশে নির্বাচিত বাটনের বিস্তারিত -->
      <div class="bg-teal-50/70 border border-teal-100 rounded-2xl p-6 space-y-3 min-h-[210px] flex flex-col justify-center">
        <span id="detailBadge" class="text-xs font-bold uppercase tracking-wider text-teal-600">Select a Button</span>
        <h3 id="detailTitle" class="text-lg font-bold text-gray-800"></h3>
        <p id="detailEn" class="text-sm text-gray-700"></p>
        <p id="detailBn" class="text-sm text-teal-900 bg-white p-3 rounded-xl border border-teal-100"></p>
      </div>
    </div>

    <div class="pt-4 border-t flex justify-end">
      <a href="<?= htmlspecialchars($target_dashboard) ?>" class="px-6 py-3 bg-teal-600 text-white text-sm font-semibold rounded-xl hover:bg-teal-700 shadow">
        Continue to Dashboard / ড্যাশবোর্ডে যান ➔
      </a>
    </div>
  </div>

  <script>
    const role = <?= json_encode($role) ?>;
    const dataMap = {
      patient: [
        { label: '📅 Book Appointment', title: 'Doctor Appointment / অ্যাপয়েন্টমেন্ট', en: 'Search specialist doctors and book an online appointment slot.', bn: 'বিশেষজ্ঞ ডাক্তার খুঁজে অনলাইনে সিরিয়াল বুক করুন।' },
        { label: '💊 My Prescriptions', title: 'Digital Prescriptions / প্রেসক্রিপশন', en: 'View and download all prescriptions issued by your doctors.', bn: 'ডাক্তারদের দেওয়া সব প্রেসক্রিপশন দেখুন ও ডাউনলোড করুন।' },
        { label: '🩸 Blood Donors', title: 'Find Blood Donors / ব্লাড ডোনার', en: 'Find verified blood donors by blood group during emergencies.', bn: 'জরুরি রক্তের প্রয়োজনে গ্রুপ অনুযায়ী ডোনার খুঁজুন।' },
        { label: '🛡️ Report Doctor', title: 'Safety & Report / অভিযোগ জানান', en: 'Report fake doctors or submit feedback directly to the admin.', bn: 'ভুয়া ডাক্তার বা যেকোনো সমস্যা সরাসরি অ্যাডমিনকে রিপোর্ট করুন।' }
      ],
      doctor: [
        { label: '📋 Today’s Appointments', title: 'Patient Serials / রোগীদের তালিকা', en: 'View scheduled patients and manage consultation status.', bn: 'আজকের রোগীদের সিরিয়াল দেখুন এবং কনসালটেশন আপডেট করুন।' },
        { label: '✍️ Write Prescription', title: 'E-Prescription / প্রেসক্রিপশন লিখুন', en: 'Create digital prescriptions that patients receive instantly.', bn: 'রোগীদের জন্য ডিজিটাল প্রেসক্রিপশন লিখে সাবমিট করুন।' },
        { label: '⚙️ Chamber Settings', title: 'Schedule & Fees / চেম্বার সেটিংস', en: 'Update your visiting hours, chamber address, and fees.', bn: 'আপনার রোগী দেখার সময়সূচি, চেম্বার ও ফি আপডেট করুন।' }
      ],
      admin: [
        { label: '👥 Manage Users', title: 'User & Doctor Control / ইউজার ম্যানেজমেন্ট', en: 'Approve new doctor registrations and manage user accounts.', bn: 'নতুন ডাক্তারদের অনুমোদন দিন এবং সব ইউজার নিয়ন্ত্রণ করুন।' },
        { label: '🚨 Fake Doctor Reports', title: 'Review Complaints / রিপোর্ট যাচাই', en: 'Investigate patient complaints and take action against fake profiles.', bn: 'রোগীদের পাঠানো অভিযোগ যাচাই করে ব্যবস্থা নিন।' },
        { label: '🩸 Blood Bank List', title: 'Donor Database / ব্লাড ব্যাংক', en: 'Monitor registered blood donors and availability status.', bn: 'নিবন্ধিত ব্লাড ডোনারদের তালিকা ও স্ট্যাটাস দেখুন।' }
      ]
    };

    const items = dataMap[role] || dataMap.patient;
    const btnList = document.getElementById('btnList');

    function showItem(idx) {
      const item = items[idx];
      document.getElementById('detailBadge').textContent = 'Selected Button / নির্বাচিত বাটন';
      document.getElementById('detailTitle').textContent = item.title;
      document.getElementById('detailEn').textContent = item.en;
      document.getElementById('detailBn').textContent = item.bn;

      Array.from(btnList.children).forEach((el, i) => {
        el.className = i === idx
          ? 'w-full text-left px-4 py-3 rounded-xl font-semibold text-sm bg-teal-600 text-white shadow transition'
          : 'w-full text-left px-4 py-3 rounded-xl font-semibold text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 transition';
      });
    }

    items.forEach((item, idx) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.textContent = item.label;
      btn.onclick = () => showItem(idx);
      btnList.appendChild(btn);
    });

    showItem(0);
  </script>
</body>
</html>