<?php
require_once __DIR__ . '/../includes/db.php';
date_default_timezone_set('Asia/Manila');

$step = 1;
$message = "";
$enteredToken = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        // STEP 1: User submits email
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(4)); // 8-character token
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $step = 2;
            $message = "✅ A reset token has been sent or provided. Please enter it below.";
            // Optional: log to server for admin use
            error_log("Reset token for $email: $token");
        } else {
            $message = "❌ No user found with that email address.";
        }
    } elseif (isset($_POST['token'])) {
        // STEP 2: User submits token
        $enteredToken = trim($_POST['token']);

        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $enteredToken);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: reset_password.php?token=" . urlencode($enteredToken));
            exit;
        } else {
            $message = "❌ Invalid or expired token.";
            $step = 2;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password | NeuroHelp</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #c0f0e8, #96b8ff);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .reset-container {
      display: flex;
      justify-content: center;
      align-items: center;
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
    }

    .reset-box {
      width: 100%;
    }

    .reset-box h2 {
      margin-bottom: 10px;
      font-size: 24px;
      color: #4b2aad;
    }

    .reset-box p {
      font-size: 14px;
      margin-bottom: 20px;
      color: #444;
    }

    form input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    form button {
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

    form button:hover {
      background-color: #5d2cd3;
    }

    .status-message {
      margin-top: 15px;
      padding: 10px;
      border-radius: 6px;
      font-size: 14px;
      text-align: center;
      background-color: #f5f5f5;
    }

    .back-link {
      margin-top: 20px;
      text-align: center;
    }

    .back-link a {
      text-decoration: none;
      color: #333;
      font-size: 14px;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <div class="reset-box">
      <h2>🔒 Forgot Password</h2>

      <?php if ($step === 1): ?>
        <p>Enter your registered email to generate a reset token.</p>
        <form method="POST">
          <input type="email" name="email" placeholder="Enter your email" required autofocus>
          <button type="submit">Generate Token</button>
        </form>

      <?php elseif ($step === 2): ?>
        <p>Enter the token provided to you.</p>
        <form method="POST">
          <input type="text" name="token" value="<?= htmlspecialchars($enteredToken) ?>" placeholder="Enter reset token" required autofocus>
          <button type="submit">Verify Token</button>
        </form>
      <?php endif; ?>

      <?php if ($message): ?>
        <div class="status-message"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <div class="back-link">
        <a href="../public/index.php">← Back to Login</a>
      </div>
    </div>
  </div>
</body>
</html>
