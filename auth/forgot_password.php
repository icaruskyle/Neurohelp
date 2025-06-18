<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store token in the database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // ✅ Send reset email
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
<html>
<head><title>Forgot Password</title></head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required><br><br>
        <input type="submit" value="Send Reset Link">
    </form>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
</body>
</html>
