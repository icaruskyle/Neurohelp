<?php
require_once _DIR_ . '/../includes/db.php';
require_once _DIR_ . '/../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $resetLink = "http://localhost/NeuroHelp/auth/reset_password.php?token=$token";
        $body = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";
        sendMail($email, "Password Reset - NeuroHelp", $body);
        $message = "✅ A password reset link has been sent to your email.";
    } else {
        $message = "❌ No user found with that email address.";
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

    form input[type="email"] {
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
    }

    .status-message:before {
      content: "ℹ️ ";
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
      <p>Enter your registered email below and we'll send you a link to reset your password.</p>
      <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
      </form>
      <?php if (isset($message)): ?>
        <div class="status-message"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>
      <div class="back-link">
        <a href="../index.html">← Back to Login</a>
      </div>
    </div>
  </div>
</body>
</html>