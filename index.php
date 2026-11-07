<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title data-i18n="idx.title">CareFlow - Hospital Management System</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, -apple-system, 'Segoe UI', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- ===== নেভিগেশন বার (সেশন চেক সহ ডাইনামিক) ===== -->
  <header class="bg-white shadow-sm sticky top-0 z-10">
    <nav class="max-w-6xl mx-auto flex items-center justify-between px-4 py-3">
      <a href="index.php" class="text-2xl font-bold text-teal-600">Care<span class="text-gray-800">Flow</span></a>

      <ul class="hidden md:flex items-center gap-6 text-sm font-medium">
        <li><a href="index.php" class="text-teal-600 font-semibold" data-i18n="nav.home">Home</a></li>
        <li><a href="booking.html" class="hover:text-teal-600" data-i18n="nav.book">Book Appointment</a></li>
        <li><a href="blood-donors.php" class="hover:text-teal-600" data-i18n="nav.blood">Blood Donors</a></li>
        <li><a href="report-doctor.php" class="hover:text-teal-600" data-i18n="nav.report">Report Fake Doctor</a></li>
      </ul>

      <div class="flex items-center gap-2">
        <button type="button" id="lang-btn" onclick="toggleLang()" class="px-3 py-1.5 text-sm font-medium border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50 transition">বাংলা</button>
        
        <!-- ইউজার লগইন করা থাকলে ড্যাশবোর্ড ও লগআউট দেখাবে, না থাকলে লগইন ও রেজিস্টার -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="patient-dashboard.php" class="px-3 sm:px-4 py-2 text-sm font-medium bg-teal-600 text-white rounded-lg hover:bg-teal-700" data-i18n="nav.dashboard">Dashboard</a>
          <a href="logout.php" class="px-3 py-2 text-sm font-medium text-red-600 hover:underline">Logout</a>
        <?php else: ?>
          <a href="login.php" class="px-3 sm:px-4 py-2 text-sm font-medium text-teal-600 hover:bg-teal-50 rounded-lg" data-i18n="nav.login">Login</a>
          <a href="register.php" class="px-3 sm:px-4 py-2 text-sm font-medium bg-teal-600 text-white rounded-lg hover:bg-teal-700" data-i18n="nav.register">Register</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <!-- ===== হিরো সেকশন + সার্চ বার ===== -->
  <section class="bg-gradient-to-r from-teal-600 to-teal-500 text-white">
    <div class="max-w-6xl mx-auto px-4 py-16 md:py-24 text-center">
      <h1 class="text-3xl md:text-5xl font-bold leading-tight" data-i18n="idx.h1">Tell us your problem. We will find the right doctor.</h1>
      <p class="mt-4 text-teal-50 max-w-2xl mx-auto" data-i18n="idx.sub">
        Describe how you feel, choose any district in Bangladesh, and see suitable doctors, including specialists abroad for serious cases.
      </p>

      <form action="booking.html" method="GET" class="mt-8 bg-white rounded-xl p-3 shadow-lg flex flex-col md:flex-row gap-3 max-w-3xl mx-auto">
        <input type="hidden" name="mode" value="symptom">
        <input type="text" name="q" required data-i18n-placeholder="idx.ph"
               class="flex-1 px-4 py-3 rounded-lg border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
        <select id="location" name="location" class="px-4 py-3 rounded-lg border border-gray-200 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white"></select>
        <button type="submit" class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700" data-i18n="idx.find">
          Find Doctors
        </button>
      </form>

      <p class="mt-4 text-sm text-teal-50">
        <span data-i18n="idx.know">Already know your doctor?</span>
        <a href="booking.html?mode=direct" class="font-semibold underline ml-1" data-i18n="idx.knowLink">Book directly →</a>
      </p>
    </div>
  </section>

  <!-- ===== ইমার্জেন্সি ব্লাড ডোনার হাইলাইট ===== -->
  <section class="max-w-6xl mx-auto px-4 -mt-8">
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 md:p-8 shadow-md">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <span class="inline-block text-xs font-bold bg-red-600 text-white px-3 py-1 rounded-full" data-i18n="em.badge">EMERGENCY</span>
          <h2 class="mt-3 text-2xl font-bold text-red-700" data-i18n="em.h">Need Blood Urgently?</h2>
          <p class="mt-1 text-gray-600 text-sm" data-i18n="em.p">Find available donors near you by blood group.</p>
        </div>
        <a href="blood-donors.php" class="text-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700" data-i18n="em.btn">
          Find a Donor
        </a>
      </div>

      <div class="mt-6 grid grid-cols-4 md:grid-cols-8 gap-2">
        <a href="blood-donors.php?group=A%2B" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">A+</a>
        <a href="blood-donors.php?group=A-" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">A-</a>
        <a href="blood-donors.php?group=B%2B" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">B+</a>
        <a href="blood-donors.php?group=B-" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">B-</a>
        <a href="blood-donors.php?group=AB%2B" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">AB+</a>
        <a href="blood-donors.php?group=AB-" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">AB-</a>
        <a href="blood-donors.php?group=O%2B" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">O+</a>
        <a href="blood-donors.php?group=O-" class="text-center py-2 bg-white border border-red-200 rounded-lg font-bold text-red-600 hover:bg-red-600 hover:text-white transition">O-</a>
      </div>
    </div>
  </section>

  <!-- ===== ডাক্তার বুক করার দুটো উপায় ===== -->
  <section class="max-w-6xl mx-auto px-4 pt-16">
    <h2 class="text-2xl md:text-3xl font-bold text-center" data-i18n="two.h">Two Ways to Book a Doctor</h2>
    <div class="mt-8 grid gap-6 md:grid-cols-2">
      <a href="booking.html?mode=symptom" class="block bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition border border-gray-100 hover:border-teal-400">
        <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-2xl">🩺</div>
        <h3 class="mt-4 font-semibold text-lg" data-i18n="two.1.h">1. Tell us your problem</h3>
        <p class="mt-2 text-sm text-gray-600" data-i18n="two.1.p">Not sure which doctor you need? Describe your symptoms and CareFlow suggests suitable doctors for you.</p>
        <span class="inline-block mt-4 text-sm font-semibold text-teal-600" data-i18n="two.1.a">Describe symptoms →</span>
      </a>

      <a href="booking.html?mode=direct" class="block bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition border border-gray-100 hover:border-teal-400">
        <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-2xl">🔍</div>
        <h3 class="mt-4 font-semibold text-lg" data-i18n="two.2.h">2. I know what I need</h3>
        <p class="mt-2 text-sm text-gray-600" data-i18n="two.2.p">Already know your problem or your doctor? Browse all doctors and book an appointment directly.</p>
        <span class="inline-block mt-4 text-sm font-semibold text-teal-600" data-i18n="two.2.a">Browse doctors →</span>
      </a>
    </div>
  </section>

  <!-- ===== আমাদের সেবা ===== -->
  <section class="max-w-6xl mx-auto px-4 py-16">
    <h2 class="text-2xl md:text-3xl font-bold text-center" data-i18n="svc.h">What You Can Do on CareFlow</h2>
    <div class="mt-10 grid gap-6 md:grid-cols-3">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-2xl">📅</div>
        <h3 class="mt-4 font-semibold text-lg" data-i18n="svc.1.h">Book Appointments</h3>
        <p class="mt-2 text-sm text-gray-600" data-i18n="svc.1.p">Find doctors across all 64 districts, and specialists abroad when you need more.</p>
        <a href="booking.html" class="inline-block mt-4 text-sm font-semibold text-teal-600 hover:underline" data-i18n="svc.1.a">Book now →</a>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-2xl">💊</div>
        <h3 class="mt-4 font-semibold text-lg" data-i18n="svc.2.h">Digital Prescriptions</h3>
        <p class="mt-2 text-sm text-gray-600" data-i18n="svc.2.p">Access your prescriptions and medical history anytime, safely.</p>
        <a href="patient-dashboard.php" class="inline-block mt-4 text-sm font-semibold text-teal-600 hover:underline" data-i18n="svc.2.a">Open dashboard →</a>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="w-12 h-12 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center text-2xl">🛡️</div>
        <h3 class="mt-4 font-semibold text-lg" data-i18n="svc.3.h">Report Fake Doctors</h3>
        <p class="mt-2 text-sm text-gray-600" data-i18n="svc.3.p">Help keep patients safe by reporting suspicious practitioners.</p>
        <a href="report-doctor.php" class="inline-block mt-4 text-sm font-semibold text-teal-600 hover:underline" data-i18n="svc.3.a">Report →</a>
      </div>
    </div>
  </section>

  <!-- ===== ফুটার ===== -->
  <footer class="bg-gray-900 text-gray-400 text-sm">
    <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row justify-between gap-2 text-center md:text-left">
      <p data-i18n="foot.copy">&copy; 2026 CareFlow Hospital Management System</p>
      <p data-i18n="foot.built">Built as a diploma project</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="i18n.js"></script>
  <script src="data.js"></script>
  <script>
    const locationSelect = document.getElementById("location");
    if (typeof fillLocationSelect === "function") {
      fillLocationSelect(locationSelect);
    }
    document.addEventListener("langchange", function () {
      if (typeof fillLocationSelect === "function") {
        fillLocationSelect(locationSelect);
      }
    });
  </script>

</body>
</html>