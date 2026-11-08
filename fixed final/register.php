<?php
/**
 * CareFlow - User Registration with Auto-Login & Tour Trigger
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');
    $role     = $_POST['role'] ?? 'patient';

    if (empty($fullName) || empty($email) || empty($password)) {
        $message = 'Please fill in all required fields.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $message = 'This email is already registered. Please use another email or login.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $pdo->beginTransaction();

                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, phone, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$fullName, $email, $passwordHash, $phone, $role]);
                $userId = $pdo->lastInsertId();

                if ($role === 'patient') {
                    $stmt = $pdo->prepare("INSERT INTO patients (user_id) VALUES (?)");
                    $stmt->execute([$userId]);
                } elseif ($role === 'doctor') {
                    $spec = $_POST['specialization'] ?? 'General Medicine';
                    $bmdc = $_POST['bmdc_reg_no'] ?? 'BMDC-' . rand(1000, 9999);
                    $stmt = $pdo->prepare("INSERT INTO doctors (user_id, specialization, bmdc_reg_no) VALUES (?, ?, ?)");
                    $stmt->execute([$userId, $spec, $bmdc]);
                }

                $pdo->commit();

                $_SESSION = array(); 
                $_SESSION['user_id']   = $userId;
                $_SESSION['full_name'] = $fullName;
                $_SESSION['email']     = $email;
                $_SESSION['role']      = $role;

                // নতুন রেজিস্ট্রেশনের পর ট্যুরের জন্য tour=1 ফ্ল্যাগসহ রিডাইরেক্ট করবে
                header('Location: patient-dashboard.php?tour=1');
                exit;
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $message = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen py-10">
  <div class="bg-white p-8 rounded-xl shadow-sm max-w-md w-full border border-gray-200">
    <h2 class="text-2xl font-bold text-teal-600 mb-6 text-center">Create CareFlow Account</h2>
    
    <?php if (!empty($message)): ?>
      <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200 font-semibold"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="register.php" method="POST" autocomplete="off" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-700">Full Name</label>
        <input type="text" name="full_name" required autocomplete="off" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 text-sm" placeholder="e.g. John Doe">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-700">Email Address</label>
        <input type="email" name="email" required autocomplete="off" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 text-sm" placeholder="user@example.com">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-700">Password</label>
        <input type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 text-sm" placeholder="••••••••">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-700">Phone Number</label>
        <input type="text" name="phone" autocomplete="off" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 text-sm" placeholder="017xxxxxxxx">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-700">Role</label>
        <select name="role" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
          <option value="patient">Patient</option>
          <option value="doctor">Doctor</option>
          <option value="admin">Admin</option>
          <option value="donor">Blood Donor</option>
        </select>
      </div>
      <button type="submit" class="w-full py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">Register</button>
    </form>
    <p class="text-xs text-center mt-4 text-gray-500">Already have an account? <a href="login.php" class="text-teal-600 font-semibold hover:underline">Login here</a></p>
  </div>
</body>
</html>