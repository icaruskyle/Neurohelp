<?php
require_once __DIR__ . '/../includes/db.php';

$token = $_GET['token'] ?? '';
$tokenValid = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Step 1: Validate token again on submission
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Step 2: Update password
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $new_pass, $token);
        $stmt->execute();

        echo "<script>
            alert('✅ Password successfully updated! Redirecting to login...');
            window.location.href = '../public/index.php';
        </script>";
        exit;
    } else {
        $message = "❌ Invalid or expired token.";
    }
} else {
    // Initial page load: check token validity
    if ($token) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $tokenValid = true;
        } else {
            $message = "❌ Invalid or expired token.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | NeuroHelp</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #e2d1f9, #c0f0e8);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .reset-box {
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .reset-box h2 {
      margin-bottom: 20px;
      color: #4b2aad;
    }

    form input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    form input[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #7a42f4;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    form input[type="submit"]:hover {
      background-color: #5d2cd3;
    }

    .message {
      margin-top: 15px;
      font-size: 14px;
      color: red;
    }
  </style>
</head>
<body>
  <div class="reset-box">
    <h2>🔐 Reset Your Password</h2>

    <?php if ($tokenValid || $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
      <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="password" placeholder="Enter new password" required>
        <input type="submit" value="Reset Password">
      </form>
    <?php else: ?>
      <p class="message"><?= htmlspecialchars($message) ?></p>
      <a href="../public/index.php">← Back to Login</a>
    <?php endif; ?>
  </div>
</body>
</html>
