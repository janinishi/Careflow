<?php
/**
 * CareFlow - Secure Login Page (Dynamic User Name from Database)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        try {
            $pdo = require __DIR__ . '/db.php';
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                $db_pass = $user['password_hash'] ?? $user['password'] ?? '';

                if ($password === $db_pass || password_verify($password, $db_pass)) {
                    // পুরনো সেশন পরিষ্কার করা
                    $_SESSION = array();

                    // ডাটাবেস টেবিলে যে নাম আছে (full_name বা name কলাম থেকে) সেটিই সেশনে সেট করা হবে
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['full_name'] = $user['full_name'] ?? $user['name'] ?? 'User'; 
                    $_SESSION['email']     = $user['email'];
                    $_SESSION['role']      = $user['role'] ?? 'patient';

                    // রোল অনুযায়ী সঠিক ড্যাশবোর্ডে রিডাইরেক্ট
                    $role = $user['role'] ?? 'patient';
                    if ($role === 'admin') {
                        header('Location: admin-dashboard.php');
                    } elseif ($role === 'doctor') {
                        header('Location: doctor-dashboard.php');
                    } else {
                        header('Location: patient-dashboard.php');
                    }
                    exit;
                } else {
                    $error = 'Invalid email or password.';
                }
            } else {
                $error = 'No account found with this email.';
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - CareFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>body { font-family: 'Hind Siliguri', system-ui, sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded-xl shadow-sm max-w-md w-full border border-gray-200">
    
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold text-teal-600 mt-1">Login to CareFlow</h2>
    </div>

    <?php if (!empty($error)): ?>
      <div class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200 font-semibold"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
        <input type="email" name="email" required autocomplete="off" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 text-sm" placeholder="user@example.com">
      </div>
      <div>
        <label class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
        <input type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 text-sm" placeholder="••••••••">
      </div>
      <button type="submit" class="w-full py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">Login</button>
    </form>
    
    <p class="text-xs text-center mt-4 text-gray-500">Don't have an account? <a href="register.php" class="text-teal-600 font-semibold hover:underline">Register here</a></p>
  </div>
</body>
</html>