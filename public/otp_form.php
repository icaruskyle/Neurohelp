<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Enter OTP</title>
</head>
<body>
    <h2>Two-Factor Authentication</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form method="POST" action="2fa_verify.php">
        <label>Enter OTP:</label>
        <input type="text" name="otp" required />
        <button type="submit">Verify</button>
    </form>
</body>
</html>