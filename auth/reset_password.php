<?php
require_once __DIR__ . '/../includes/db.php';

$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->bind_param("ss", $new_pass, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = "✅ Password updated. You can now <a href='../public/index.php'>login</a>.";
    } else {
        $message = "❌ Invalid or expired token.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
    <h2>Reset Password</h2>
    <link rel="stylesheet" href="reset.css">
    <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="password" placeholder="New Password" required><br><br>
        <input type="submit" value="Reset Password">
    </form>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
</body>
</html>
