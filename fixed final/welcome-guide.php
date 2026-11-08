<?php
/**
 * CareFlow - First-time User Welcome & Feature Guide
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// যদি ইউজার লগইন করা না থাকে, তবে লগইন পেজে পাঠাবে
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$display_name = $_SESSION['full_name'] ?? 'User';
$role = $_SESSION['role'] ?? 'patient';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to CareFlow - Guide</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
  <div class="bg-white p-8 rounded-2xl shadow-md max-w-2xl w-full border border-gray-100 space-y-6">
    
    <div class="text-center space-y-2">
      <span class="text-4xl">🎉</span>
      <h1 class="text-2xl font-bold text-teal-600">স্বাগতম, <?= htmlspecialchars($display_name) ?>!</h1>
      <p class="text-sm text-gray-500">CareFlow-তে আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে। নিচে দেখে নিন কোথায় কী কাজ রয়েছে:</p>
    </div>

    <!-- গাইড লিস্ট বা কার্ডস -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
      <div class="bg-teal-50 p-4 rounded-xl border border-teal-100 space-y-1">
        <h3 class="font-bold text-teal-800">📅 ড্যাশবোর্ড ও অ্যাপয়েন্টমেন্ট</h3>
        <p class="text-xs text-gray-600">এখান থেকে আপনি আপনার আপকামিং ডক্টর ভিজিট, পূর্বের প্রেসক্রিপশন এবং মেডিকেল হিস্ট্রি দেখতে ও নতুন বুকিং করতে পারবেন।</p>
      </div>

      <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 space-y-1">
        <h3 class="font-bold text-blue-800">💊 প্রেসক্রিপশন ম্যানেজমেন্ট</h3>
        <p class="text-xs text-gray-600">ডাক্তারদের দেওয়া প্রেসক্রিপশনগুলো ডিজিটালি স্টোর থাকবে এবং যেকোনো সময় ডাউনলোড বা দেখতে পারবেন।</p>
      </div>

      <div class="bg-red-50 p-4 rounded-xl border border-red-100 space-y-1">
        <h3 class="font-bold text-red-800">🩸 ব্লাড ডোনারস</h3>
        <p class="text-xs text-gray-600">জরুরি প্রয়োজনে রক্তের গ্রুপ অনুযায়ী ডোনারদের তালিকা খুঁজে পেতে এবং যোগাযোগ করতে পারবেন।</p>
      </div>

      <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 space-y-1">
        <h3 class="font-bold text-amber-800">🛡️ রিপোর্ট বা সাপোর্ট</h3>
        <p class="text-xs text-gray-600">কোনো সমস্যা হলে কিংবা কোনো বিষয়ে অভিযোগ বা মতামত থাকলে এই সেকশন থেকে তা জানাতে পারবেন।</p>
      </div>
    </div>

    <div class="pt-4 text-center">
      <?php 
        // রোল অনুযায়ী ড্যাশবোর্ডের লিংক ঠিক করা
        $target_dashboard = 'patient-dashboard.php';
        if ($role === 'admin') $target_dashboard = 'admin-dashboard.php';
        if ($role === 'doctor') $target_dashboard = 'doctor-dashboard.php';
      ?>
      <a href="<?= $target_dashboard ?>" class="inline-block w-full py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-sm text-sm">
        ড্যাশবোর্ডে প্রবেশ করুন ➔
      </a>
    </div>

  </div>
</body>
</html>