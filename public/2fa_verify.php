<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: index.php");
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_otp = trim($_POST['otp']);

    $stmt = $conn->prepare("SELECT otp_code, otp_expires FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $input_otp === $user['otp_code'] && new DateTime() < new DateTime($user['otp_expires'])) {
        // ✅ OTP is valid
        $_SESSION['2fa_verified'] = true;

        // Optional: clear OTP after use
        $clear = $conn->prepare("UPDATE users SET otp_code = NULL, otp_expires = NULL WHERE id = ?");
        $clear->bind_param("i", $user_id);
        $clear->execute();

        $_SESSION['2fa_verified'] = true;

        $redirectTo = $_SESSION['next_url'] ?? '../public/dashboard1.php';
        unset($_SESSION['next_url']); // Clear after using

        header("Location: $redirectTo");
        exit;

    } else {
        $error = "Invalid or expired OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2FA Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f5e8ff, #b5e8e0);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .otp-container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .otp-container h2 {
            margin-bottom: 20px;
            color: #4b2aad;
        }

        form input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
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

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Enter the OTP sent to your email</h2>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="otp" placeholder="Enter 6-digit OTP" required>
            <input type="submit" value="Verify">
        </form>
    </div>
</body>
</html>
