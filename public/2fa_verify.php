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

$redirectTo = $_SESSION['next_url'] ?? '/NeuroHelp/public/dashboard.php';
unset($_SESSION['next_url']); // Clear after using

header("Location: $redirectTo");
exit;

    } else {
        $error = "Invalid or expired OTP.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification</title>
</head>
<body>
    <h2>Enter the OTP sent to your email</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="otp" placeholder="Enter 6-digit OTP" required>
        <input type="submit" value="Verify">
    </form>
</body>
</html>
