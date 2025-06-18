<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) exit;

$otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
$expiry = (new DateTime())->modify('+5 minutes')->format('Y-m-d H:i:s');

// Store OTP
$stmt = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires = ? WHERE id = ?");
$stmt->bind_param("ssi", $otp, $expiry, $user_id);
$stmt->execute();

// Fetch email
$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$email = $stmt->get_result()->fetch_assoc()['email'];

// Send email
sendMail($email, "Your OTP Code", "Your NeuroHelp OTP is: <b>$otp</b>. It expires in 5 minutes.");

// Redirect to 2FA verify page
$_SESSION['2fa_redirect'] = $_GET['next'] ?? 'dashboard.php';
header("Location: ../public/2fa_verify.php");
exit;
